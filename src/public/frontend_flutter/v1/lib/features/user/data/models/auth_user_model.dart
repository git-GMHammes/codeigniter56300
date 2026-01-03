// frontend_flutter/v1/lib/features/user/data/models/auth_user_model.dart

import 'dart:convert';

class AuthUserModel {
  final int httpCode;
  final String status;
  final String message;
  final String? token;
  final User user;

  AuthUserModel({
    required this.httpCode,
    required this.status,
    required this.message,
    required this.token,
    required this.user,
  });

  factory AuthUserModel.fromJson(Map<String, dynamic> json) {
    return AuthUserModel(
      httpCode: json['http_code'],
      status: json['status'],
      message: json['message'],
      token: json['data']['token'],
      user: User.fromJson(json['data']['user']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'http_code': httpCode,
      'status': status,
      'message': message,
      'data': {'token': token, 'user': user.toJson()},
    };
  }
}

class User {
  final String id;
  final String username;
  final String? lastLogin;
  final String? createdAt;
  final String? updatedAt;
  final String? deletedAt;

  User({
    required this.id,
    required this.username,
    this.lastLogin,
    this.createdAt,
    this.updatedAt,
    this.deletedAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      username: json['user'],
      lastLogin: json['last_login'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
      deletedAt: json['deleted_at'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user': username,
      'last_login': lastLogin,
      'created_at': createdAt,
      'updated_at': updatedAt,
      'deleted_at': deletedAt,
    };
  }
}
