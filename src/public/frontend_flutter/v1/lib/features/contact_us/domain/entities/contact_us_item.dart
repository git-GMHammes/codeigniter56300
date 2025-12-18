/// Entidade de domínio para mensagens de contato
class ContactUsItem {
  final int? id;
  final String name;
  final String email;
  final String category;
  final String subject;
  final String message;
  final String status;

  const ContactUsItem({
    this.id,
    required this.name,
    required this.email,
    required this.category,
    required this.subject,
    required this.message,
    this.status = 'pending',
  });

  /// Status possíveis: pending, read, answered, closed
  static const List<String> statusOptions = [
    'pending',
    'read',
    'answered',
    'closed',
  ];

  /// Categorias disponíveis
  static const List<String> categoryOptions = [
    'Dúvida',
    'Sugestão',
    'Reclamação',
    'Elogio',
    'Suporte Técnico',
    'Financeiro',
    'Comercial',
    'Parceria',
    'Outros',
  ];
}
