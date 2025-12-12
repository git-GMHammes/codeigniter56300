// Exemplo de registro local para get_it. Integre dentro do seu injector.dart principal.
// Requer get_it: ^X.Y.Z no pubspec.yaml se ainda não tiver.
import 'package:get_it/get_it.dart';
import '../data/datasources/auth_remote_datasource.dart';
import '../data/repositories/auth_repository_impl.dart';
import '../../domain/repositories/auth_repository.dart';

void registerAuthModule(GetIt di, {required String baseUrl}) {
  // se já existir registro, remover e registrar novamente (opcional)
  if (!di.isRegistered<AuthRemoteDataSource>()) {
    di.registerLazySingleton<AuthRemoteDataSource>(
      () => AuthRemoteDataSource(baseUrl: baseUrl),
    );
  }
  if (!di.isRegistered<AuthRepository>()) {
    di.registerLazySingleton<AuthRepository>(
      () => AuthRepositoryImpl(remote: di<AuthRemoteDataSource>()),
    );
  }
}
