import '../../domain/entities/contact_us_item.dart';

class ContactUsItemModel extends ContactUsItem {
  const ContactUsItemModel({
    super.id,
    required super.name,
    required super.email,
    required super. category,
    required super.subject,
    required super.message,
    super. status = 'pending',
  });

  /// Converte de JSON para Model
  factory ContactUsItemModel. fromJson(Map<String, dynamic> json) {
    return ContactUsItemModel(
      id: json['id'] as int?,
      name: json['name'] as String?  ?? '',
      email: json['email'] as String? ?? '',
      category: json['category'] as String? ?? '',
      subject: json['subject'] as String? ??  '',
      message:  json['message'] as String? ?? '',
      status: json['status'] as String?  ?? 'pending',
    );
  }

  /// Converte de Model para JSON (para envio à API)
  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'email': email,
      'category':  category,
      'subject': subject,
      'message': message,
      'status': status,
    };
  }

  /// Cria Model a partir da Entity
  factory ContactUsItemModel. fromEntity(ContactUsItem entity) {
    return ContactUsItemModel(
      id: entity.id,
      name: entity.name,
      email: entity.email,
      category:  entity.category,
      subject: entity. subject,
      message: entity.message,
      status: entity.status,
    );
  }
}