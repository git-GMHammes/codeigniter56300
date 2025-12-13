import 'package:flutter/material.dart';

class LabeledTextField extends StatelessWidget {
  final String label;
  final TextEditingController controller;
  final TextInputType keyboardType;
  final bool readOnly;
  final String? hintText;
  final int? maxLines;
  final ValueChanged<String>? onChanged;
  final bool obscureText;

  const LabeledTextField({
    super.key,
    required this.label,
    required this.controller,
    this.keyboardType = TextInputType.text,
    this.readOnly = false,
    this.hintText,
    this.maxLines = 1,
    this.onChanged,
    this.obscureText = false,
  });

  @override
  Widget build(BuildContext context) {
    final labelStyle =
        Theme.of(context).textTheme.titleMedium ??
        const TextStyle(fontSize: 14);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: labelStyle),
        const SizedBox(height: 6),
        TextField(
          controller: controller,
          keyboardType: keyboardType,
          readOnly: readOnly,
          maxLines: maxLines,
          obscureText: obscureText,
          decoration: InputDecoration(isDense: true, hintText: hintText),
          onChanged: onChanged,
        ),
      ],
    );
  }
}
