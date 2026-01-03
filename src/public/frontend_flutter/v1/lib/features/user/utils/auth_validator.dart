// frontend_flutter/v1/lib/features/user/utils/auth_validator.dart

import 'package:shared_preferences/shared_preferences.dart';

class AuthValidator {
  static const String _tokenKey = 'user_token';

  // Verifica se há um token válido armazenado
  Future<bool> isLoggedIn() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString(_tokenKey);
    return token != null && token.isNotEmpty;
  }

  // Salva o token localmente
  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
  }

  // Remove o token armazenado, efetuando logout
  Future<void> clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_tokenKey);
  }

  // Recupera o token armazenado
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_tokenKey);
  }
}
