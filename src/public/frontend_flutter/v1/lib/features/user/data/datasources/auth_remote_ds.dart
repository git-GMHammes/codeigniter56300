import 'package:dio/dio.dart';
import '../../../../core/config/env.dart';

class AuthRemoteDataSource {
  final Dio dio;

  AuthRemoteDataSource({Dio? dio})
      : dio = dio ??
            Dio(
              BaseOptions(
                baseUrl: Env.baseUrl,
                headers: {'Content-Type': 'application/json'},
              ),
            );

  Future<Map<String, dynamic>> registerUser(
    String user,
    String password,
    String passwordConfirm,
  ) async {
    try {
      final response = await dio.post(
        '/api/v1/user-management',
        data: {
          'user': user,
          'password': password,
          'password_confirm': passwordConfirm,
        },
      );
      return response.data;
    } on DioException catch (e) {
      if (e.response != null) {
        return e.response!.data;
      }
      throw Exception('Erro de comunicação');
    }
  }
}