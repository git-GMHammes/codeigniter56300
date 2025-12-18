import 'package:dartz/dartz.dart';
import '../../../../core/errors/failure.dart';
import '../../domain/entities/user.dart';
import '../../domain/repositories/auth_repository.dart';
import '../models/user_model.dart';
import '../datasources/auth_remote_ds.dart';

class AuthRepositoryImpl implements AuthRepository {
  final AuthRemoteDataSource remote;

  AuthRepositoryImpl(this.remote);

  @override
  Future<Either<Failure, User>> registerUser(
    String user,
    String password,
    String passwordConfirm,
  ) async {
    try {
      final resp = await remote.registerUser(user, password, passwordConfirm);
      if (resp['http_code'] == 201) {
        final userModel = UserModel.fromJson(resp['data']);
        return Right(userModel.toEntity());
      } else if (resp['http_code'] == 422) {
        final val = resp['data']['validation'] as Map<String, dynamic>;
        final msg = val.values.join('\n');
        return Left(Failure(msg));
      } else {
        return Left(Failure('Erro inesperado!'));
      }
    } catch (e) {
      return Left(Failure('Erro de comunicação'));
    }
  }
}
