import '../../domain/entities/user.dart';

class UserModel {
  final int id;

  UserModel({required this.id});

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] as int,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
      };

  User toEntity() => User(id: id);
}