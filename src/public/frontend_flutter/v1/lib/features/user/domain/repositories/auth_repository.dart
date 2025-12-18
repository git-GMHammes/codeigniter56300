import 'package:dartz/dartz.dart';
import '../../../../core/errors/failure.dart';
import '../entities/user.dart';

abstract class AuthRepository {
  Future<Either<Failure, User>> registerUser(
    String user,
    String password,
    String passwordConfirm,
  );
}