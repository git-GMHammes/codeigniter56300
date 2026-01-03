import 'dart:io';
import 'package:dartz/dartz.dart';
import '../../../../core/errors/failure.dart';
import '../../domain/entities/user.dart';
import '../../domain/repositories/register_repository.dart';
import '../models/user_model.dart';
import '../datasources/register_remote_ds.dart';

class RegisterRepositoryImpl implements RegisterRepository {
  final RegisterRemoteDataSource remote;

  RegisterRepositoryImpl(this.remote);

  // ════════════════════════════════════════════════════════════════════════
  // STEP 1: Registrar credenciais (user_management)
  // ════════════════════════════════════════════════════════════════════════

  @override
  Future<Either<Failure, User>> registerUser(
    String user,
    String password,
    String passwordConfirm,
  ) async {
    try {
      final resp = await remote.registerUser(user, password, passwordConfirm);

      if (resp['http_code'] == 201) {
        // Sucesso - extrai o ID
        final userModel = UserModel.fromJson(resp);
        return Right(userModel.toEntity());
      } else if (resp['http_code'] == 422) {
        // Erro de validação
        final val = resp['data']['validation'] as Map<String, dynamic>;
        final msg = val.values.join('\n');
        return Left(Failure(msg));
      } else {
        return Left(Failure('Erro inesperado!'));
      }
    } catch (e) {
      return Left(Failure('Erro de comunicação: $e'));
    }
  }

  // ════════════════════════════════════════════════════════════════════════
  // STEP 2: Registrar dados pessoais (user_customer)
  // ════════════════════════════════════════════════════════════════════════

  @override
  Future<Either<Failure, void>> registerCustomer({
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
    try {
      // Envia para a API
      final resp = await remote.registerCustomer(
        userId: userId,
        name: name,
        cpf: cpf,
        mail: mail,
        phone: phone,
        whatsapp: whatsapp,
        dateBirth: dateBirth,
        zipCode: zipCode,
        address: address,
        uploadFilesPath: uploadFilesPath,
      );

      if (resp['http_code'] == 201) {
        // Sucesso
        return const Right(null);
      } else if (resp['http_code'] == 422) {
        // Erro de validação
        final val = resp['data']['validation'] as Map<String, dynamic>;
        final msg = val.values.join('\n');
        return Left(Failure(msg));
      } else {
        return Left(Failure(resp['message'] ?? 'Erro inesperado!'));
      }
    } catch (e) {
      return Left(Failure('Erro de comunicação: $e'));
    }
  }
}
