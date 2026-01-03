import 'package:flutter/material.dart';
import 'home_sobre_card.dart';
import 'home_project_card.dart';

class HomeRow2Card extends StatelessWidget {
  const HomeRow2Card({super.key});

  @override
  Widget build(BuildContext context) {
    return Row(
      children:  [
        Expanded(child: HomeSobreCard()),
        const SizedBox(width: 12),
        Expanded(child: HomeProjectCard()),
      ],
    );
  }
}