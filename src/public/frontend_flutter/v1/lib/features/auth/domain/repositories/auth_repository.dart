abstract class AuthRepository {
  /// Retorna um Map contendo a resposta do backend (ajuste conforme necessidade)
  Future<Map<String, dynamic>> login({
    required String user,
    required String password,
  });

  /// Retorna o user_id criado
  Future<int> registerCredentials({
    required String user,
    required String password,
    required String passwordConfirm,
  });

  /// Retorna o id do customer criado
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
  });
}
