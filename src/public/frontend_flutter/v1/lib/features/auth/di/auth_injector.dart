// frontend_flutter/v1/lib/features/auth/di/auth_injector.dart
import 'package:get_it/get_it.dart';
import 'package:frontend_flutter_v1/features/auth/data/datasources/auth_remote_datasource.dart';
import 'package:frontend_flutter_v1/features/auth/data/repositories/auth_repository_impl.dart';
import 'package:frontend_flutter_v1/features/auth/domain/repositories/auth_repository.dart';

void registerAuthModule(GetIt di, {required String baseUrl}) {
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
