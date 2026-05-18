package com.absensi.nfc.mobile

import android.content.Intent
import android.nfc.NfcAdapter
import android.provider.Settings
import io.flutter.embedding.android.FlutterActivity
import io.flutter.embedding.engine.FlutterEngine
import io.flutter.plugin.common.MethodChannel

class MainActivity : FlutterActivity() {
	private val channelName = "com.absensi.nfc/hce"

	override fun configureFlutterEngine(flutterEngine: FlutterEngine) {
		super.configureFlutterEngine(flutterEngine)

		MethodChannel(flutterEngine.dartExecutor.binaryMessenger, channelName)
			.setMethodCallHandler { call, result ->
				when (call.method) {
					"setToken" -> {
						val token = call.argument<String>("token") ?: ""
						HceService.setToken(token)
						result.success(null)
					}
					"setEnabled" -> {
						val enabled = call.argument<Boolean>("enabled") ?: false
						HceService.setEnabled(enabled)
						result.success(null)
					}
					"isNfcEnabled" -> {
						val adapter = NfcAdapter.getDefaultAdapter(this)
						result.success(adapter?.isEnabled == true)
					}
					"openNfcSettings" -> {
						val intent = try {
							Intent(Settings.ACTION_NFC_SETTINGS)
						} catch (e: Exception) {
							Intent(Settings.ACTION_WIRELESS_SETTINGS)
						}
						startActivity(intent)
						result.success(null)
					}
					else -> result.notImplemented()
				}
			}
	}
}
