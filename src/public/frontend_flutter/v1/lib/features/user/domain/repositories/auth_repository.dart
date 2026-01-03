// frontend_flutter/v1/lib/features/user/domain/repositories/auth_repository.dart

import '../../data/models/auth_user_model.dart';

abstract class AuthRepository {
  Future<AuthUserModel> loginUser(String username, String password);
}
