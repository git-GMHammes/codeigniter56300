import '../../domain/entities/user.dart';

// ══════════════════════════════════════════════════════════════════════════
// MODEL 1: UserManagementModel
// Representa a tabela: user_management (Credenciais - Step 1)
// ══════════════════════════════════════════════════════════════════════════

/// Model para a tabela user_management
/// Usado no Step 1 do cadastro (credenciais)
class UserManagementModel {
  final int id;
  final String user;
  final String? password; // Opcional - só enviamos, nunca recebemos

  UserManagementModel({required this.id, required this.user, this.password});

  // ──────────────────────────────────────────────────────────────────────────
  // FROM JSON - Converte resposta da API para Model
  // ──────────────────────────────────────────────────────────────────────────

  /// Converte JSON do backend para UserManagementModel
  ///
  /// Exemplo de resposta da API:
  /// ```json
  /// {
  ///   "http_code": 201,
  ///   "status": "success",
  ///   "message": "Usuário criado com sucesso.",
  ///   "data": {
  ///     "id": 86
  ///   }
  /// }
  /// ```
  factory UserManagementModel.fromJson(Map<String, dynamic> json) {
    // A API retorna os dados dentro de "data"
    final data = json['data'] as Map<String, dynamic>? ?? json;

    return UserManagementModel(
      id: data['id'] as int,
      user: data['user'] as String? ?? '',
    );
  }

  // ──────────────────────────────────────────────────────────────────────────
  // TO JSON - Converte Model para enviar à API
  // ──────────────────────────────────────────────────────────────────────────

  /// Converte UserManagementModel para JSON para enviar ao backend
  ///
  /// Exemplo de payload:
  /// ```json
  /// {
  ///   "user": "joao_silva",
  ///   "password": "senha123"
  /// }
  /// ```
  Map<String, dynamic> toJson() {
    final json = <String, dynamic>{'user': user};

    // Só inclui password se estiver preenchido (no cadastro)
    if (password != null) {
      json['password'] = password;
    }

    return json;
  }

  // ──────────────────────────────────────────────────────────────────────────
  // TO ENTITY - Converte Model para Entity (Domain)
  // ──────────────────────────────────────────────────────────────────────────

  User toEntity() => User(id: id);
}

// ══════════════════════════════════════════════════════════════════════════
// MODEL 2: UserCustomerModel
// Representa a tabela: user_customer (Dados Pessoais - Step 2)
// ══════════════════════════════════════════════════════════════════════════

/// Model para a tabela user_customer
/// Usado no Step 2 do cadastro (dados pessoais)
class UserCustomerModel {
  final int? id;
  final int userId;
  final String name;
  final String? cpf;
  final String? mail;
  final String? phone;
  final String? whatsapp;
  final DateTime? dateBirth;
  final String? zipCode;
  final String? address;
  final String? profile;

  UserCustomerModel({
    this.id,
    required this.userId,
    required this.name,
    this.cpf,
    this.mail,
    this.phone,
    this.whatsapp,
    this.dateBirth,
    this.zipCode,
    this.address,
    this.profile,
  });

  // ──────────────────────────────────────────────────────────────────────────
  // FROM JSON - Converte resposta da API para Model
  // ──────────────────────────────────────────────────────────────────────────

  /// Converte JSON do backend para UserCustomerModel
  factory UserCustomerModel.fromJson(Map<String, dynamic> json) {
    final data = json['data'] as Map<String, dynamic>? ?? json;

    return UserCustomerModel(
      id: data['id'] as int?,
      userId: data['user_id'] as int,
      name: data['name'] as String,
      cpf: data['cpf'] as String?,
      mail: data['mail'] as String?,
      phone: data['phone'] as String?,
      whatsapp: data['whatsapp'] as String?,
      dateBirth:
          data['date_birth'] != null
              ? DateTime.parse(data['date_birth'] as String)
              : null,
      zipCode: data['zip_code'] as String?,
      address: data['address'] as String?,
      profile: data['profile'] as String?,
    );
  }

  // ──────────────────────────────────────────────────────────────────────────
  // TO JSON - Converte Model para enviar à API
  // ──────────────────────────────────────────────────────────────────────────

  /// Converte UserCustomerModel para JSON para enviar ao backend
  ///
  /// Exemplo de payload:
  /// ```json
  /// {
  ///   "user_id": 86,
  ///   "name": "João Silva",
  ///   "cpf": "12345678900",
  ///   "mail": "joao@email.com",
  ///   "phone": "21987876766",
  ///   "whatsapp": "21987876766",
  ///   "date_birth": "1990-05-15",
  ///   "zip_code": "01310100",
  ///   "address": "Av Paulista, 1000",
  ///   "profile": "base64_ou_url_da_foto"
  /// }
  /// ```
  Map<String, dynamic> toJson() {
    final json = <String, dynamic>{
      'user_id': userId, // ← IMPORTANTE! FK para user_management
      'name': name,
    };

    // Campos opcionais - só inclui se preenchidos
    if (cpf != null && cpf!.isNotEmpty) {
      json['cpf'] = cpf!.replaceAll(RegExp(r'\D'), ''); // Remove máscaras
    }
    if (mail != null && mail!.isNotEmpty) {
      json['mail'] = mail;
    }
    if (phone != null && phone!.isNotEmpty) {
      json['phone'] = phone!.replaceAll(RegExp(r'\D'), ''); // Remove máscaras
    }
    if (whatsapp != null && whatsapp!.isNotEmpty) {
      json['whatsapp'] = whatsapp!.replaceAll(
        RegExp(r'\D'),
        '',
      ); // Remove máscaras
    }
    if (dateBirth != null) {
      // Formata data como YYYY-MM-DD
      json['date_birth'] =
          '${dateBirth!.year.toString().padLeft(4, '0')}-'
          '${dateBirth!.month.toString().padLeft(2, '0')}-'
          '${dateBirth!.day.toString().padLeft(2, '0')}';
    }
    if (zipCode != null && zipCode!.isNotEmpty) {
      json['zip_code'] = zipCode!.replaceAll(
        RegExp(r'\D'),
        '',
      ); // Remove máscara
    }
    if (address != null && address!.isNotEmpty) {
      json['address'] = address;
    }
    if (profile != null && profile!.isNotEmpty) {
      json['profile'] = profile;
    }

    return json;
  }
}

// ══════════════════════════════════════════════════════════════════════════
// MANTENDO COMPATIBILIDADE
// ══════════════════════════════════════════════════════════════════════════

/// Alias para UserManagementModel para manter compatibilidade
/// com código existente que usa UserModel
typedef UserModel = UserManagementModel;
