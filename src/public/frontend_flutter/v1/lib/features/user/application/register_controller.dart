import '../domain/usecases/register_user.dart';

class RegisterController {
  final RegisterUser registerUserUseCase;

  RegisterController(this.registerUserUseCase);

  Future<String?> registrar(
    String usuario,
    String senha,
    String confirmarSenha,
  ) async {
    final result = await registerUserUseCase(usuario, senha, confirmarSenha);
    return result.fold((failure) => failure.message, (user) => null);
  }
}
