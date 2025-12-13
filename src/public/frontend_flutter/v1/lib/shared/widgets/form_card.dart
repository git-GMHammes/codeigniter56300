import 'dart:math' as math;
import 'package:flutter/material.dart';

class FormCard extends StatelessWidget {
  final Widget child;
  final double maxWidth;
  final EdgeInsetsGeometry padding;

  final double? topOffset;

  const FormCard({
    super.key,
    required this.child,
    this.maxWidth = 520,
    this.padding = const EdgeInsets.all(20),
    this.topOffset,
  });

  @override
  Widget build(BuildContext context) {
    return LayoutBuilder(
      builder: (context, constraints) {
        final double screenW = constraints.maxWidth;
        final bool isNarrow = screenW < 600;
        final double cardWidth =
            isNarrow ? screenW * 0.92 : math.min(maxWidth, screenW * 0.5);

        final double defaultTop =
            kToolbarHeight + MediaQuery.of(context).padding.top + 8;
        final double top = topOffset ?? defaultTop;

        return SingleChildScrollView(
          padding: const EdgeInsets.symmetric(vertical: 0, horizontal: 16),
          child: ConstrainedBox(
            constraints: BoxConstraints(minHeight: constraints.maxHeight),

            child: Align(
              alignment: Alignment.topCenter,
              child: Padding(
                padding: EdgeInsets.only(top: top),
                child: SizedBox(
                  width: cardWidth,
                  child: Container(
                    decoration: BoxDecoration(
                      color: Theme.of(context).cardColor.withAlpha(230),
                      borderRadius: BorderRadius.circular(12),
                      boxShadow: [
                        BoxShadow(
                          color: const Color.fromRGBO(0, 0, 0, 0.04),
                          blurRadius: 8,
                          offset: const Offset(0, 2),
                        ),
                      ],
                      border: Border.all(color: Colors.black12, width: 0.5),
                    ),
                    padding: padding,
                    child: child,
                  ),
                ),
              ),
            ),
          ),
        );
      },
    );
  }
}
