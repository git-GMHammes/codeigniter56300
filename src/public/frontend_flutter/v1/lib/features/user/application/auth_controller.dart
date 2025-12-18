import '../domain/usecases/register_user.dart';

class AuthController {
  final RegisterUser registerUserUseCase;

  AuthController(this.registerUserUseCase);

  Future<String?> registrar(String usuario, String senha, String confirmarSenha) async {
    final result = await registerUserUseCase(usuario, senha, confirmarSenha);
    return result.fold(
      (failure) => failure.message,
      (user) => null,
    );
  }
}