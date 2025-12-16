import 'package:flutter/material.dart';

class LoginCard extends StatefulWidget {
  const LoginCard({super.key});

  @override
  State<LoginCard> createState() => _LoginCardState();
}

class _LoginCardState extends State<LoginCard> {
  final _formKey = GlobalKey<FormState>();
  final _userCtrl = TextEditingController();
  final _passCtrl = TextEditingController();
  final _userFocus = FocusNode();
  final _passFocus = FocusNode();

  @override
  void dispose() {
    _userCtrl.dispose();
    _passCtrl.dispose();
    _userFocus.dispose();
    _passFocus.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

    // Borda preta ou branca conforme tema
    final borderColor = isDark ? Colors.white : Colors.black;
    // Botão azul escuro (tema claro) ou roxo claro (tema escuro)
    final buttonColor = isDark ? Colors.purpleAccent.shade100 : Colors.indigo.shade900;

    return Center(
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: 520),
        child: Container(
          padding: const EdgeInsets.all(18),
          decoration: BoxDecoration(
            // Efeito 3D com sombras e leve gradiente
            borderRadius: BorderRadius.circular(16),
            gradient: isDark
                ? LinearGradient(
                    colors: [Colors.grey.shade900, Colors.grey.shade800],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  )
                : LinearGradient(
                    colors: [Colors.white, Colors.grey.shade100],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
            boxShadow: [
              BoxShadow(
                color: isDark ? Colors.black.withOpacity(0.6) : Colors.grey.withOpacity(0.25),
                offset: const Offset(4, 6),
                blurRadius: 12,
              ),
              BoxShadow(
                color: isDark ? Colors.white.withOpacity(0.02) : Colors.white.withOpacity(0.6),
                offset: const Offset(-3, -3),
                blurRadius: 6,
                spreadRadius: -2,
              ),
            ],
            border: Border.all(color: borderColor.withOpacity(0.12)),
          ),
          child: Form(
            key: _formKey,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                // Título do card
                Align(
                  alignment: Alignment.centerLeft,
                  child: Text(
                    'Login de acesso',
                    style: theme.textTheme.titleLarge,
                  ),
                ),
                const SizedBox(height: 12),
                // Campo Usuário
                TextFormField(
                  controller: _userCtrl,
                  focusNode: _userFocus,
                  textInputAction: TextInputAction.next,
                  onFieldSubmitted: (_) => _passFocus.requestFocus(),
                  decoration: InputDecoration(
                    labelText: 'Usuário',
                    floatingLabelBehavior: FloatingLabelBehavior.auto, // retrai para cima ao focar
                    filled: true,
                    fillColor: isDark ? Colors.grey.shade800 : Colors.white,
                    contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
                    enabledBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide(color: borderColor.withOpacity(0.9)),
                    ),
                    focusedBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide(color: borderColor, width: 1.6),
                    ),
                  ),
                ),
                // Espaçamento curto (apenas o suficiente para um alerta pequeno)
                const SizedBox(height: 8),
                // Campo Senha
                TextFormField(
                  controller: _passCtrl,
                  focusNode: _passFocus,
                  obscureText: true,
                  textInputAction: TextInputAction.done,
                  decoration: InputDecoration(
                    labelText: 'Senha',
                    floatingLabelBehavior: FloatingLabelBehavior.auto,
                    filled: true,
                    fillColor: isDark ? Colors.grey.shade800 : Colors.white,
                    contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
                    enabledBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide(color: borderColor.withOpacity(0.9)),
                    ),
                    focusedBorder: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide(color: borderColor, width: 1.6),
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                // Botão de login com bordas 3D arredondadas
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () {
                      // Somente UI: validação local e placeholder
                      if (_formKey.currentState?.validate() ?? true) {
                        // ação de login (a integrar depois)
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Ação de login (não integrada)')),
                        );
                      }
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: buttonColor,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(14),
                      ),
                      elevation: 8,
                    ),
                    child: Text(
                      'Entrar',
                      style: theme.textTheme.titleMedium?.copyWith(
                        color: isDark ? Colors.black87 : Colors.white,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}