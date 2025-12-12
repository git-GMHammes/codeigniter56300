class CreateUserResponse {
  final int id;
  CreateUserResponse({required this.id});

  factory CreateUserResponse.fromJson(Map<String, dynamic> json) {
    final data = json['data'] as Map<String, dynamic>? ?? {};
    final id = (data['id'] as num?)?.toInt() ?? 0;
    return CreateUserResponse(id: id);
  }
}
