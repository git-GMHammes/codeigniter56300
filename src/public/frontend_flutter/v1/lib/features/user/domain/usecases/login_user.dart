// frontend_flutter/v1/lib/features/user/domain/usecases/login_user.dart

import '../repositories/auth_repository.dart';
import '../../data/models/auth_user_model.dart';

class LoginUser {
  final AuthRepository authRepository;

  LoginUser({required this.authRepository});

  Future<AuthUserModel> execute(String username, String password) async {
    try {
      return await authRepository.loginUser(username, password);
    } catch (error) {
      throw Exception('Erro ao realizar login: $error');
    }
  }
}