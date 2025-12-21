import 'package:dio/dio.dart';
import '../../../../core/config/env.dart';
import '../models/user_model.dart';

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

  // ════════════════════════════════════════════════════════════════════════
  // STEP 1: Cadastrar credenciais (user_management)
  // ════════════════════════════════════════════════════════════════════════

  /// POST /api/v1/user-management
  /// Retorna: { "data": { "id": 86 } }
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

  // ════════════════════════════════════════════════════════════════════════
  // STEP 2: Cadastrar dados pessoais (user_customer)
  // ════════════════════════════════════════════════════════════════════════

  /// POST /api/v1/user-customer
  /// Envia: { "user_id": 86, "name": "João", "cpf": "123..." }
  Future<Map<String, dynamic>> registerCustomer(
    UserCustomerModel customer,
  ) async {
    try {
      final response = await dio.post(
        '/api/v1/user-customer',
        data: customer.toJson(), // Usa o toJson do Model
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
