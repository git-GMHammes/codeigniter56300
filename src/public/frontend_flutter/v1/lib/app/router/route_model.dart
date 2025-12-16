import 'package:flutter/widgets.dart';

/// Modelo de rota modular com suporte a sub-rotas (children).
class ModuleRoute {
  /// path pode ser absoluto (começando com '/') ou relativo (ex: 'messages').
  final String path;
  final WidgetBuilder builder;
  final List<ModuleRoute> children;

  const ModuleRoute({
    required this.path,
    required this.builder,
    this.children = const [],
  });
}