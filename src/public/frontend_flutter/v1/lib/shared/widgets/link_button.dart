import 'package:flutter/material.dart';

class LinkButton extends StatelessWidget {
  final VoidCallback onPressed;
  final String label;

  const LinkButton({super.key, required this.onPressed, required this.label});

  @override
  Widget build(BuildContext context) {
    return TextButton(
      onPressed: onPressed,
      child: Text(
        label,
        style: TextStyle(color: Theme.of(context).colorScheme.primary),
      ),
    );
  }
}
