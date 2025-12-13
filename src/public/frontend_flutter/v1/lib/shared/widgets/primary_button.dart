import 'package:flutter/material.dart';

class PrimaryButton extends StatelessWidget {
  final VoidCallback onPressed;
  final String label;
  final EdgeInsetsGeometry padding;

  const PrimaryButton({
    super.key,
    required this.onPressed,
    required this.label,
    this.padding = const EdgeInsets.symmetric(horizontal: 24, vertical: 10),
  });

  @override
  Widget build(BuildContext context) {
    final primary = Theme.of(context).colorScheme.primary;
    // converte a opacidade (0.0..1.0) para 0..255 inteiro
    final bgColor = primary.withAlpha((0.12 * 255).round());

    return ElevatedButton(
      onPressed: onPressed,
      style: ElevatedButton.styleFrom(
        padding: padding,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
        elevation: 0,
        backgroundColor: bgColor,
        foregroundColor: primary,
        textStyle: const TextStyle(fontSize: 16),
      ),
      child: Text(label),
    );
  }
}
