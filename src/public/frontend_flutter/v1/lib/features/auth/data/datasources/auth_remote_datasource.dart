import 'dart:convert';
import 'package:http/http.dart' as http;

class AuthRemoteDataSource {
  final String baseUrl;

  AuthRemoteDataSource({required this.baseUrl});

  Future<Map<String, dynamic>> login({
    required String user,
    required String password,
  }) async {
    final uri = Uri.parse('$baseUrl/api/v1/user-management');
    final resp = await http.post(
      uri,
      headers: {'Content-Type': 'application/json'},
      body: json.encode({'user': user, 'password': password}),
    );

    final decoded = json.decode(resp.body) as Map<String, dynamic>;
    if (resp.statusCode >= 200 && resp.statusCode < 300) {
      return decoded;
    } else {
      throw Exception(
        'Login failed: ${decoded['message'] ?? resp.reasonPhrase}',
      );
    }
  }

  /// Parte 1: cria usuário (user-management). Retorna id do usuário criado.
  Future<int> registerCredentials({
    required String user,
    required String password,
    required String passwordConfirm,
  }) async {
    final uri = Uri.parse('$baseUrl/api/v1/user-management');
    final resp = await http.post(
      uri,
      headers: {'Content-Type': 'application/json'},
      body: json.encode({
        'user': user,
        'password': password,
        'password_confirm': passwordConfirm,
      }),
    );

    final decoded = json.decode(resp.body) as Map<String, dynamic>;
    if (resp.statusCode == 201 || (decoded['http_code'] == 201)) {
      // tenta obter id em data.id
      final data = decoded['data'] as Map<String, dynamic>?;
      final id =
          data != null
              ? (data['id'] as num).toInt()
              : throw Exception('Missing id in response');
      return id;
    } else {
      throw Exception(
        'Register credentials failed: ${decoded['message'] ?? resp.reasonPhrase}',
      );
    }
  }

  /// Parte 2: cria o perfil/cliente (customer-management)
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
    final uri = Uri.parse('$baseUrl/api/v1/customer-management');
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
    };

    final resp = await http.post(
      uri,
      headers: {'Content-Type': 'application/json'},
      body: json.encode(body),
    );

    final decoded = json.decode(resp.body) as Map<String, dynamic>;
    if (resp.statusCode == 201 || (decoded['http_code'] == 201)) {
      final data = decoded['data'] as Map<String, dynamic>?;
      final id =
          data != null
              ? (data['id'] as num).toInt()
              : throw Exception('Missing id in response');
      return id;
    } else {
      throw Exception(
        'Register profile failed: ${decoded['message'] ?? resp.reasonPhrase}',
      );
    }
  }
}
