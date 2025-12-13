import '../../domain/repositories/auth_repository.dart';
import '../datasources/auth_remote_datasource.dart';

class AuthRepositoryImpl implements AuthRepository {
  final AuthRemoteDataSource remote;

  AuthRepositoryImpl({required this.remote});

  @override
  Future<Map<String, dynamic>> login({
    required String user,
    required String password,
  }) {
    return remote.login(user: user, password: password);
  }

  @override
  Future<int> registerCredentials({
    required String user,
    required String password,
    required String passwordConfirm,
  }) {
    return remote.registerCredentials(
      user: user,
      password: password,
      passwordConfirm: passwordConfirm,
    );
  }

  @override
  Future<int> registerProfile({
    required int userId,
    required String name,
    String? profile,
    String? phone,
    String? dateBirth,
    String? zipCode,
    String? address,
    String? cpf,
    String? whatsapp,
    String? mail,
  }) {
    return remote.registerProfile(
      userId: userId,
      name: name,
      profile: profile,
      phone: phone,
      dateBirth: dateBirth,
      zipCode: zipCode,
      address: address,
      cpf: cpf,
      whatsapp: whatsapp,
      mail: mail,
    );
  }
}
