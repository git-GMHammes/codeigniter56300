import 'package:dio/dio.dart';
import '../../../../core/config/env.dart'; // Certifique-se de ajustar o caminho conforme necessário
import '../models/auth_user_model.dart';

class AuthRemoteDataSource {
  final Dio dio;

  AuthRemoteDataSource({Dio? dio})
    : dio =
          dio ??
          Dio(
            BaseOptions(
              baseUrl: Env.baseUrl,
              headers: {'Content-Type': 'application/json'},
            ),
          );

  Future<AuthUserModel> loginUser(String username, String password) async {
    const String endpoint = '/api/v1/user-management/login';
    try {
      // Log de depuração
      print('Enviando requisição para $endpoint');
      print(
        'Corpo da requisição: {"user": "$username", "password": "$password"}',
      );

      final response = await dio.post(
        endpoint,
        data: {"user": username, "password": password},
      );

      print('Resposta da API: ${response.data}');

      if (response.statusCode == 200) {
        return AuthUserModel.fromJson(response.data);
      } else {
        throw Exception(
          'Erro na resposta da API: Código ${response.statusCode}',
        );
      }
    } catch (error) {
      print('Erro durante o login: $error');
      throw Exception('Erro na autenticação: $error');
    }
  }
}
