import 'package:flutter/material.dart';
import '../widgets/contact_us_form_card.dart';

class ContactUsPage extends StatelessWidget {
  const ContactUsPage({super.key});

  // ══════════════════════════════════════════════════════════════════════════
  // CONFIGURAÇÕES DO ÍCONE
  // ══════════════════════════════════════════════════════════════════════════
  static const double _iconSize = 80;
  static const double _iconContainerHeight = 120;

  // ══════════════════════════════════════════════════════════════════════════
  // BUILDER DO ÍCONE
  // ══════════════════════════════════════════════════════════════════════════
  Widget _buildContactIcon(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

    return SizedBox(
      height: _iconContainerHeight,
      child: Center(
        child:  Icon(
          Icons.chat_bubble_outline,
          size:  _iconSize,
          color: isDark ? Colors.purpleAccent. shade100 : Colors.indigo.shade700,
        ),
      ),
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // BUILD PRINCIPAL
  // ══════════════════════════════════════════════════════════════════════════
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      appBar: AppBar(title: const Text('Fale Conosco'), centerTitle: true),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(12.0),
        child: Column(
          children: [
            // ─────────────────────────────────────────────────────────────────
            // ÍCONE FALE CONOSCO
            // ─────────────────────────────────────────────────────────────────
            _buildContactIcon(context),
            const SizedBox(height: 16),

            // ─────────────────────────────────────────────────────────────────
            // FORMULÁRIO DE CONTATO
            // ─────────────────────────────────────────────────────────────────
            const ContactUsFormCard(),
            const SizedBox(height: 16),

            // ─────────────────────────────────────────────────────────────────
            // ESPAÇO PARA PROPAGANDAS
            // ─────────────────────────────────────────────────────────────────
            const SizedBox(
              height:  80,
              child: Center(
                child: Text('Espaço reservado para propagandas e avisos'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}