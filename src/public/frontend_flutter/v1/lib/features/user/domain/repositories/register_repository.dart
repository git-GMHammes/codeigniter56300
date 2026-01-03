import 'dart:io';
import 'package:dartz/dartz.dart';
import '../../../../core/errors/failure.dart';
import '../entities/user.dart';

abstract class RegisterRepository {
  // ════════════════════════════════════════════════════════════════════════
  // STEP 1: Registrar credenciais (user_management)
  // ════════════════════════════════════════════════════════════════════════

  /// Cadastra usuário e senha
  /// Retorna User com o ID gerado
  Future<Either<Failure, User>> registerUser(
    String user,
    String password,
    String passwordConfirm,
  );

  // ════════════════════════════════════════════════════════════════════════
  // STEP 2: Registrar dados pessoais (user_customer)
  // ════════════════════════════════════════════════════════════════════════

  /// Cadastra dados pessoais do usuário
  /// Requer o user_id retornado do Step 1
  Future<Either<Failure, void>> registerCustomer({
    required int userId, // FK do user_management.id
    required String name, // Obrigatório
    String? cpf, // Opcional
    String? mail, // Opcional
    String? phone, // Opcional
    String? whatsapp, // Opcional
    DateTime? dateBirth, // Opcional
    String? zipCode, // Opcional
    String? address, // Opcional
    File? uploadFilesPath, // Opcional
  });
}
