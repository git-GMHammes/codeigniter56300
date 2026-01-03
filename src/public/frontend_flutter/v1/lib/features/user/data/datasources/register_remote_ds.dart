import 'dart:io';
import 'dart:convert';
import 'package:dio/dio.dart';
import 'package:http/http.dart' as http;
import '../../../../core/config/env.dart';

class RegisterRemoteDataSource {
  final Dio dio;

  RegisterRemoteDataSource({Dio? dio})
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
  // STEP 2: Cadastrar dados pessoais (customer_management) COM ARQUIVO
  // ════════════════════════════════════════════════════════════════════════

  /// POST /api/v1/customer-management
  /// Envia dados + arquivo via multipart
  Future<Map<String, dynamic>> registerCustomer({
    required int userId,
    required String name,
    String? cpf,
    String? mail,
    String? phone,
    String? whatsapp,
    DateTime? dateBirth,
    String? zipCode,
    String? address,
    File? uploadFilesPath,
  }) async {
    final uri = Uri.parse('${Env.baseUrl}/api/v1/customer-management');

    // Criar multipart request
    final request = http.MultipartRequest('POST', uri);

    // Adicionar campos de texto
    request.fields['user_id'] = userId.toString();
    request.fields['name'] = name;
    if (cpf != null) request.fields['cpf'] = cpf;
    if (mail != null) request.fields['mail'] = mail;
    if (phone != null) request.fields['phone'] = phone;
    if (whatsapp != null) request.fields['whatsapp'] = whatsapp;
    if (zipCode != null) request.fields['zip_code'] = zipCode;
    if (address != null) request.fields['address'] = address;
    if (dateBirth != null) {
      request.fields['date_birth'] = dateBirth.toIso8601String();
    }

    // ✅ ADICIONAR ARQUIVO COM NOME CORRETO
    if (uploadFilesPath != null) {
      request.files.add(
        await http.MultipartFile.fromPath(
          'uploadFilesPath[]',
          uploadFilesPath.path,
        ),
      );
    }

    // Enviar request
    final streamedResponse = await request.send();
    final response = await http.Response.fromStream(streamedResponse);

    // ========== PRINTS PARA DEBUG ==========
    print('==========================================');
    print('RESPOSTA DA API (STEP 2):');
    print('Status Code: ${response.statusCode}');
    print('Body: ${response.body}');
    print('==========================================');
    // ========================================

    if (response.statusCode == 200 || response.statusCode == 201) {
      // Parse JSON
      final Map<String, dynamic> jsonResponse = json.decode(response.body);

      print('JSON Parseado: $jsonResponse');

      return jsonResponse;
    } else {
      throw Exception('Erro ao cadastrar: ${response.body}');
    }
  }
}
