import 'dart:io';
import 'package:dartz/dartz.dart';
import '../../../../core/errors/failure.dart';
import '../entities/user.dart';
import '../repositories/register_repository.dart';

class RegisterUser {
  final RegisterRepository repository;

  RegisterUser(this.repository);

  // ════════════════════════════════════════════════════════════════════════
  // STEP 1: Cadastrar credenciais
  // ════════════════════════════════════════════════════════════════════════

  /// Cadastra user + password
  /// Retorna User com o ID gerado
  Future<Either<Failure, User>> call(
    String user,
    String password,
    String passwordConfirm,
  ) {
    return repository.registerUser(user, password, passwordConfirm);
  }

  // ════════════════════════════════════════════════════════════════════════
  // STEP 2: Cadastrar dados pessoais
  // ════════════════════════════════════════════════════════════════════════

  /// Cadastra dados pessoais com o user_id do Step 1
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
  }) {
    return repository.registerCustomer(
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
  }
}
