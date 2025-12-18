import 'package:dartz/dartz.dart';
import '../../../../core/errors/failure.dart';
import '../entities/user.dart';
import '../repositories/auth_repository.dart';

class RegisterUser {
  final AuthRepository repository;

  RegisterUser(this.repository);

  Future<Either<Failure, User>> call(
    String user,
    String password,
    String passwordConfirm,
  ) {
    return repository.registerUser(user, password, passwordConfirm);
  }
}