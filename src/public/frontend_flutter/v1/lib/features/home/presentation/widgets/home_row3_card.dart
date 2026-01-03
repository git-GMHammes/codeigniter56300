import 'package:flutter/material.dart';
import 'home_login_card.dart';
import 'home_fale_card.dart';

class HomeRow3Card extends StatelessWidget {
  const HomeRow3Card({super.key});

  @override
  Widget build(BuildContext context) {
    return Row(
      children:  [
        Expanded(child: HomeLoginCard()),
        const SizedBox(width: 12),
        Expanded(child: HomeFaleCard()),
      ],
    );
  }
}