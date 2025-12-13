import 'dart:async';
import 'dart:convert';
import 'dart:io';

import 'package:http/http.dart' as http;

/// Exceção lançada pela camada de API
class ApiException implements Exception {
  final String message;
  final int? statusCode;

  ApiException(this.message, {this.statusCode});

  @override
  String toString() => 'ApiException(status: $statusCode, message: $message)';
}

/// Data source responsável por chamadas HTTP relacionadas à autenticação/usuário.
class AuthRemoteDataSource {
  final String baseUrl;
  final http.Client _client;
  final bool _clientOwned;

  /// baseUrl deve ser algo como 'https://habilidade.com/codeigniter56300/public'
  AuthRemoteDataSource({required this.baseUrl, http.Client? client})
    : _client = client ?? http.Client(),
      _clientOwned = client == null;

  /// Fecha o client se este datasource criou o client
  void dispose() {
    if (_clientOwned) {
      _client.close();
    }
  }

  Duration get _timeout => const Duration(seconds: 10);

  Map<String, String> get _defaultHeaders => const {
    'Content-Type': 'application/json; charset=utf-8',
    'Accept': 'application/json',
  };

  Future<Map<String, dynamic>> _postJson(
    Uri uri, {
    required Map<String, dynamic> body,
    Map<String, String>? extraHeaders,
  }) async {
    final headers = {
      ..._defaultHeaders,
      if (extraHeaders != null) ...extraHeaders,
    };

    try {
      final resp = await _client
          .post(uri, headers: headers, body: json.encode(body))
          .timeout(_timeout);

      // resp.body é não-nulo; apenas trate string vazia
      final respBody = resp.body.trim().isEmpty ? '{}' : resp.body;

      Map<String, dynamic> decoded;
      try {
        decoded = json.decode(respBody) as Map<String, dynamic>;
      } on FormatException {
        // resposta não JSON — encapsular e lançar
        throw ApiException(
          'Invalid JSON response',
          statusCode: resp.statusCode,
        );
      }

      // sucesso (2xx)
      if (resp.statusCode >= 200 && resp.statusCode < 300) {
        return decoded;
      } else {
        final msg = decoded['message'] ?? resp.reasonPhrase ?? 'Unknown error';
        throw ApiException(msg, statusCode: resp.statusCode);
      }
    } on TimeoutException {
      throw ApiException('Request timed out after ${_timeout.inSeconds}s');
    } on SocketException catch (e) {
      throw ApiException('Network error: ${e.message}');
    } on http.ClientException catch (e) {
      throw ApiException('HTTP client error: ${e.message}');
    } catch (e) {
      // fallback genérico
      throw ApiException(e.toString());
    }
  }

  /// Login -> endpoint: {baseUrl}/api/v1/user-management/login
  Future<Map<String, dynamic>> login({
    required String user,
    required String password,
  }) async {
    final uri = Uri.parse(
      '${baseUrl.replaceAll(RegExp(r'/+$'), '')}/api/v1/user-management/login',
    );
    return await _postJson(uri, body: {'user': user, 'password': password});
  }

  /// Cria credenciais de login -> POST {baseUrl}/api/v1/user-management
  /// Retorna id do usuário criado (int)
  Future<int> registerCredentials({
    required String user,
    required String password,
    required String passwordConfirm,
  }) async {
    final uri = Uri.parse(
      '${baseUrl.replaceAll(RegExp(r'/+$'), '')}/api/v1/user-management',
    );
    final decoded = await _postJson(
      uri,
      body: {
        'user': user,
        'password': password,
        'password_confirm': passwordConfirm,
      },
    );

    final data = decoded['data'] as Map<String, dynamic>?;
    if (data == null) {
      throw ApiException('Missing data in response');
    }
    final idVal = data['id'];
    if (idVal is num) {
      return idVal.toInt();
    } else {
      throw ApiException('Invalid id in response');
    }
  }

  /// Completa cadastro de perfil -> POST {baseUrl}/api/v1/customer-management
  Future<int> registerProfile({
    required int userId,
    required String name,
    String? profile,
    String? phone,
    String? dateBirth,
    String? zipCode,
    String? address,
    String? cpf,
    String? whatsapp,
    String? mail,
  }) async {
    final uri = Uri.parse(
      '${baseUrl.replaceAll(RegExp(r'/+$'), '')}/api/v1/customer-management',
    );
    final body = {
      'user_id': userId,
      'name': name,
      'profile': profile,
      'phone': phone,
      'date_birth': dateBirth,
      'zip_code': zipCode,
      'address': address,
      'cpf': cpf,
      'whatsapp': whatsapp,
      'mail': mail,
    }..removeWhere((_, v) => v == null);

    final decoded = await _postJson(uri, body: body);

    final data = decoded['data'] as Map<String, dynamic>?;
    if (data == null) {
      throw ApiException('Missing data in response');
    }
    final idVal = data['id'];
    if (idVal is num) {
      return idVal.toInt();
    } else {
      throw ApiException('Invalid id in response');
    }
  }
}
