import 'package:flutter/material.dart';
import '../domain/usecases/login_user.dart';
import '../data/models/auth_user_model.dart';

class AuthController extends ChangeNotifier {
  final LoginUser loginUserUseCase;

  AuthController({required this.loginUserUseCase});

  bool _isLoading = false;
  bool get isLoading => _isLoading;

  AuthUserModel? _authUser;
  AuthUserModel? get authUser => _authUser;

  Future<void> login(String username, String password) async {
    _isLoading = true;
    notifyListeners();

    try {
      final user = await loginUserUseCase.execute(username, password);
      _authUser = user;

      // Você pode persistir o token aqui utilizando SharedPreferences ou outro método.
      print('Token: ${user.token}');
    } catch (error) {
      print('Erro ao realizar login: $error');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  void logout() {
    _authUser = null;
    // Você pode limpar o token persistido aqui.
    notifyListeners();
  }
}
