#include <Wire.h>
#include <Adafruit_PN532.h>
#include <LiquidCrystal.h>
#include <WiFi.h>
#include <WiFiClientSecure.h>
#include <HTTPClient.h>

// ==================== KONFIGURASI WIFI ====================
const char* ssid = "ffyf";
const char* password = "";

// API production SITEXA (pakai HTTPS)
const char* serverUrl = "https://sitexa.my.id/absensi_api/absen.php";

// ==================== PIN ====================
LiquidCrystal lcd(19, 23, 5, 13, 12, 14);

#define SDA_PIN 21
#define SCL_PIN 22
#define BUZZER_PIN 18  // Jika tidak bunyi, coba: 4, 15, 2, 27, atau 26
#define ID_ALAT 1

Adafruit_PN532 nfc(SDA_PIN, SCL_PIN);
WiFiClientSecure secureClient;

// ==================== FUNGSI BUZZER ====================
void testBuzzer() {
  Serial.println("\n[BEEP TEST] Testing buzzer hardware...");
  for (int i = 0; i < 5; i++) {
    digitalWrite(BUZZER_PIN, HIGH);
    Serial.print("HIGH ");
    delay(300);
    digitalWrite(BUZZER_PIN, LOW);
    Serial.print("LOW ");
    delay(300);
  }
  Serial.println("\n[BEEP TEST] Test complete!");
}

void beepSuccess() {
  Serial.println("[BEEP] Success - 2 beeps");
  for (int i = 0; i < 2; i++) {
    digitalWrite(BUZZER_PIN, HIGH);
    delay(200);
    digitalWrite(BUZZER_PIN, LOW);
    delay(150);
  }
}

void beepWarning() {
  Serial.println("[BEEP] Warning - 3 beeps");
  for (int i = 0; i < 3; i++) {
    digitalWrite(BUZZER_PIN, HIGH);
    delay(120);
    digitalWrite(BUZZER_PIN, LOW);
    delay(120);
  }
}

void beepError() {
  Serial.println("[BEEP] Error - long beep");
  digitalWrite(BUZZER_PIN, HIGH);
  delay(600);
  digitalWrite(BUZZER_PIN, LOW);
}

void beepCardDetected() {
  Serial.println("[BEEP] Card detected - short beep");
  digitalWrite(BUZZER_PIN, HIGH);
  delay(100);
  digitalWrite(BUZZER_PIN, LOW);
}

// ==================== FUNGSI LCD ====================
void showReadyScreen() {
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("SITEXA READY");
  lcd.setCursor(0, 1);
  lcd.print("Tempel Kartu");
}

// ==================== FUNGSI WIFI ====================
bool connectWiFi() {
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Connecting WiFi");
  lcd.setCursor(0, 1);
  lcd.print(ssid);
  
  Serial.println("\n[WIFI] Connecting to WiFi...");
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 30) {
    delay(500);
    Serial.print(".");
    lcd.setCursor(attempts % 16, 1);
    lcd.print(".");
    attempts++;
  }
  
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\n[WIFI] Connected!");
    Serial.print("[WIFI] IP: ");
    Serial.println(WiFi.localIP());
    
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi OK!");
    lcd.setCursor(0, 1);
    lcd.print(WiFi.localIP());
    
    beepSuccess();
    delay(2000);
    showReadyScreen();
    return true;
  }
  
  Serial.println("\n[WIFI] Connection FAILED!");
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("WiFi GAGAL!");
  lcd.setCursor(0, 1);
  lcd.print("Cek Koneksi");
  
  beepError();
  return false;
}

// ==================== FUNGSI NFC ====================
String uidToString(uint8_t* uid, uint8_t uidLength) {
  String uidString = "";
  for (uint8_t i = 0; i < uidLength; i++) {
    if (uid[i] < 0x10) uidString += "0";
    uidString += String(uid[i], HEX);
  }
  uidString.toUpperCase();
  return uidString;
}

void waitForCardRemoved() {
  uint8_t uid[7];
  uint8_t uidLength;
  
  // Tunggu sampai kartu dilepas
  while (nfc.readPassiveTargetID(PN532_MIFARE_ISO14443A, uid, &uidLength, 100)) {
    delay(100);
  }
  Serial.println("[NFC] Card removed");
}

// ==================== FUNGSI SERVER ====================
void handleResponse(const String& response) {
  Serial.print("[SERVER] Response: ");
  Serial.println(response);
  
  if (response == "BERHASIL") {
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("ABSEN BERHASIL");
    lcd.setCursor(0, 1);
    lcd.print("Terima Kasih");
    beepSuccess();
    return;
  }
  
  if (response == "SUDAH ABSEN") {
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("SUDAH ABSEN");
    lcd.setCursor(0, 1);
    lcd.print("HARI INI");
    beepWarning();
    return;
  }
  
  if (response == "KARTU TIDAK TERDAFTAR") {
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("KARTU TIDAK");
    lcd.setCursor(0, 1);
    lcd.print("TERDAFTAR");
    beepError();
    return;
  }
  
  if (response == "DB_ERROR") {
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("DB ERROR");
    lcd.setCursor(0, 1);
    lcd.print("Hubungi Admin");
    beepError();
    return;
  }
  
  // Response tidak dikenali
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("RESP:");
  lcd.setCursor(0, 1);
  lcd.print(response.substring(0, 15));
  beepError();
}

bool sendAbsensi(const String& uidString) {
  Serial.println("[API] Sending attendance data...");
  
  // Cek koneksi WiFi
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("[WIFI] Reconnecting...");
    if (!connectWiFi()) {
      lcd.clear();
      lcd.setCursor(0, 0);
      lcd.print("WIFI ERROR");
      beepError();
      return false;
    }
  }
  
  HTTPClient http;
  http.begin(secureClient, serverUrl);
  http.setTimeout(15000);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  
  String postData = "uid=" + uidString + "&id_alat=" + String(ID_ALAT);
  Serial.print("[API] POST Data: ");
  Serial.println(postData);
  
  int httpCode = http.POST(postData);
  
  if (httpCode > 0) {
    String response = http.getString();
    response.trim();
    Serial.print("[API] HTTP ");
    Serial.print(httpCode);
    Serial.print(" | Response: '");
    Serial.print(response);
    Serial.println("'");
    
    handleResponse(response);
    http.end();
    return true;
  }
  
  // HTTP Error
  Serial.print("[API] HTTP Error: ");
  Serial.println(httpCode);
  
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("SERVER ERROR");
  lcd.setCursor(0, 1);
  lcd.print("HTTP: ");
  lcd.print(httpCode);
  
  beepError();
  http.end();
  return false;
}

// ==================== SETUP ====================
void setup() {
  Serial.begin(115200);
  delay(1000);
  
  Serial.println("\n\n=================================");
  Serial.println("SITEXA NFC Attendance System");
  Serial.println("=================================");
  
  // Inisialisasi buzzer
  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(BUZZER_PIN, LOW);
  Serial.print("[BUZZER] Initialized on pin ");
  Serial.println(BUZZER_PIN);
  
  // Test buzzer hardware
  testBuzzer();
  
  // Inisialisasi LCD
  lcd.begin(16, 2);
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("SITEXA System");
  lcd.setCursor(0, 1);
  lcd.print("Initializing...");
  Serial.println("[LCD] Initialized");
  
  // Setup WiFi client untuk HTTPS
  secureClient.setInsecure();
  Serial.println("[HTTPS] Client configured");
  
  // Connect WiFi
  if (!connectWiFi()) {
    Serial.println("[ERROR] WiFi connection failed - entering retry loop");
    while (true) {
      delay(5000);
      if (connectWiFi()) {
        break;
      }
    }
  }
  
  // Inisialisasi NFC
  Serial.println("[NFC] Initializing PN532...");
  nfc.begin();
  
  uint32_t versiondata = nfc.getFirmwareVersion();
  if (!versiondata) {
    Serial.println("[ERROR] PN532 not found!");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("PN532 ERROR");
    lcd.setCursor(0, 1);
    lcd.print("Check Wiring");
    beepError();
    while (true) {
      delay(1000);
    }
  }
  
  Serial.print("[NFC] Found PN532 v");
  Serial.print((versiondata>>24) & 0xFF, DEC);
  Serial.print(".");
  Serial.println((versiondata>>16) & 0xFF, DEC);
  
  nfc.SAMConfig();
  Serial.println("[NFC] Configured and ready!");
  
  // System ready
  beepSuccess();
  showReadyScreen();
  Serial.println("[SYSTEM] Ready to scan cards!");
  Serial.println("=================================\n");
}

// ==================== LOOP ====================
void loop() {
  uint8_t uid[7] = {0};
  uint8_t uidLength;
  
  // Cek kartu NFC (non-blocking, 100ms timeout)
  if (!nfc.readPassiveTargetID(PN532_MIFARE_ISO14443A, uid, &uidLength, 100)) {
    delay(100);
    return;
  }
  
  // Kartu terdeteksi
  String uidString = uidToString(uid, uidLength);
  Serial.println("\n--- CARD DETECTED ---");
  Serial.print("[NFC] UID: ");
  Serial.println(uidString);
  Serial.print("[NFC] Length: ");
  Serial.println(uidLength);
  
  // Beep pendek saat kartu terdeteksi
  beepCardDetected();
  
  // Tampilkan proses
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("PROSES...");
  lcd.setCursor(0, 1);
  lcd.print(uidString.substring(0, 16));
  
  // Kirim ke server
  sendAbsensi(uidString);
  
  // Tunggu kartu dilepas
  Serial.println("[NFC] Waiting for card removal...");
  waitForCardRemoved();
  
  // Delay sebelum kembali ke ready screen
  delay(2000);
  showReadyScreen();
  Serial.println("--- READY FOR NEXT CARD ---\n");
}
