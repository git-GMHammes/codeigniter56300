import '../../domain/repositories/auth_repository.dart';
import '../datasources/auth_remote_ds.dart';
import '../models/auth_user_model.dart';

class AuthRepositoryImpl implements AuthRepository {
  final AuthRemoteDataSource remoteDataSource;

  AuthRepositoryImpl({required this.remoteDataSource});

  @override
  Future<AuthUserModel> loginUser(String username, String password) async {
    try {
      print('Chamando AuthRemoteDataSource.loginUser...');
      final resp = await remoteDataSource.loginUser(username, password);

      if (resp.httpCode == 200) {
        print(
          'Usuário autenticado: ${resp.user.username}, Token: ${resp.token}',
        );
        return resp;
      } else if (resp.httpCode == 401) {
        throw Exception('Credenciais incorretas.');
      } else {
        throw Exception('Erro inesperado: ${resp.message}');
      }
    } catch (error) {
      print('Erro no AuthRepositoryImpl: $error');
      throw Exception('Erro no AuthRepositoryImpl: $error');
    }
  }
}
