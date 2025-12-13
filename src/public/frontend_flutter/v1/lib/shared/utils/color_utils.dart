import 'package:flutter/material.dart';

extension ColorExt on Color {
  /// Mantém a precisão do double ao criar uma variação com opacidade.
  /// Extrai componentes a partir de `toARGB32()` (compatível entre versões).
  /// Uso: `primary.withOpacitySafe(0.12)`
  Color withOpacitySafe(double opacity) {
    // normaliza opacidade para [0.0, 1.0]
    double op = opacity;
    if (op.isNaN) op = 0.0;
    if (op < 0.0) op = 0.0;
    if (op > 1.0) op = 1.0;

    // obter inteiro ARGB 0xAARRGGBB de forma explícita (não usar value)
    final int v = toARGB32();
    final int r = (v >> 16) & 0xFF;
    final int g = (v >> 8) & 0xFF;
    final int b = v & 0xFF;

    // Color.fromRGBO espera (int r, int g, int b, double opacity)
    return Color.fromRGBO(r, g, b, op);
  }

  /// Alternativa que converte opacidade 0.0..1.0 para alpha 0..255.
  /// Uso: `primary.withOpacityAlpha(0.12)`
  Color withOpacityAlpha(double opacity) {
    double op = opacity;
    if (op.isNaN) op = 0.0;
    if (op < 0.0) op = 0.0;
    if (op > 1.0) op = 1.0;

    return withAlpha((op * 255).round());
  }
}