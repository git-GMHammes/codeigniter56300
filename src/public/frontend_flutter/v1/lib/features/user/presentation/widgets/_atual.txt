import 'package:flutter/material.dart';

class LoginCard extends StatefulWidget {
  final Function(String user, String password)? onSubmit;

  const LoginCard({super.key, this.onSubmit});

  @override
  State<LoginCard> createState() => _LoginCardState();
}

class _LoginCardState extends State<LoginCard> {
  // ══════════════════════════════════════════════════════════════════════════
  // CONFIGURAÇÕES
  // ══════════════════════════════════════════════════════════════════════════
  static const double _iconSize = 80;
  static const double _iconSpacing = 50;
  static const double _maxWidth = 520;

  // ══════════════════════════════════════════════════════════════════════════
  // CONTROLLERS E FOCUS NODES
  // ══════════════════════════════════════════════════════════════════════════
  final _formKey = GlobalKey<FormState>();
  final _userCtrl = TextEditingController();
  final _passCtrl = TextEditingController();
  final _userFocus = FocusNode();
  final _passFocus = FocusNode();

  bool _isSubmitting = false;
  bool _obscurePassword = true;

  // ══════════════════════════════════════════════════════════════════════════
  // LIFECYCLE
  // ══════════════════════════════════════════════════════════════════════════
  @override
  void dispose() {
    _userCtrl.dispose();
    _passCtrl.dispose();
    _userFocus.dispose();
    _passFocus.dispose();
    super.dispose();
  }

  // ══════════════════════════════════════════════════════════════════════════
  // SUBMIT
  // ══════════════════════════════════════════════════════════════════════════
  void _submitForm() {
    if (_formKey.currentState?.validate() ?? false) {
      setState(() => _isSubmitting = true);

      if (widget.onSubmit != null) {
        widget.onSubmit!(_userCtrl.text.trim(), _passCtrl.text.trim());
      } else {
        // Caso não esteja integrado à lógica de autenticação
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Funcionalidade de login ainda em desenvolvimento'),
            backgroundColor: Colors.orange,
          ),
        );
      }

      setState(() => _isSubmitting = false);
    }
  }

  // ══════════════════════════════════════════════════════════════════════════
  // VALIDATORS
  // ══════════════════════════════════════════════════════════════════════════
  String? _validateUser(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Usuário é obrigatório';
    }
    if (value.trim().length < 3) {
      return 'Usuário deve ter pelo menos 3 caracteres';
    }
    return null;
  }

  String? _validatePassword(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Senha é obrigatória';
    }
    if (value.trim().length < 4) {
      return 'Senha deve ter pelo menos 4 caracteres';
    }
    return null;
  }

  // ══════════════════════════════════════════════════════════════════════════
  // INPUT DECORATION BUILDER
  // ══════════════════════════════════════════════════════════════════════════
  InputDecoration _buildInputDecoration({
    required String label,
    required IconData icon,
    required Color borderColor,
    required bool isDark,
    Widget? suffixIcon,
  }) {
    return InputDecoration(
      labelText: label,
      prefixIcon: Icon(icon),
      suffixIcon: suffixIcon,
      floatingLabelBehavior: FloatingLabelBehavior.auto,
      filled: true,
      fillColor: isDark ? Colors.grey.shade800 : Colors.white,
      contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: BorderSide(color: borderColor.withOpacity(0.3)),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: BorderSide(color: borderColor, width: 1.6),
      ),
      errorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(color: Colors.red, width: 1.2),
      ),
      focusedErrorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(color: Colors.red, width: 1.6),
      ),
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // CONTAINER DECORATION BUILDER
  // ══════════════════════════════════════════════════════════════════════════
  BoxDecoration _buildContainerDecoration(bool isDark, Color borderColor) {
    return BoxDecoration(
      borderRadius: BorderRadius.circular(16),
      gradient:
          isDark
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
          color:
              isDark
                  ? Colors.black.withOpacity(0.6)
                  : Colors.grey.withOpacity(0.25),
          offset: const Offset(4, 6),
          blurRadius: 12,
        ),
        BoxShadow(
          color:
              isDark
                  ? Colors.white.withOpacity(0.02)
                  : Colors.white.withOpacity(0.6),
          offset: const Offset(-3, -3),
          blurRadius: 6,
          spreadRadius: -2,
        ),
      ],
      border: Border.all(color: borderColor.withOpacity(0.12)),
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // FIELD BUILDERS
  // ══════════════════════════════════════════════════════════════════════════

  /// Ícone:  Cadeado
  Widget _buildLockIcon(bool isDark) {
    return Icon(
      Icons.lock_outline,
      size: _iconSize,
      color: isDark ? Colors.purpleAccent.shade100 : Colors.indigo.shade700,
    );
  }

  /// Header: Título
  Widget _buildHeader(ThemeData theme) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Login de Acesso',
          style: theme.textTheme.titleLarge?.copyWith(
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          'Informe suas credenciais para continuar',
          style: theme.textTheme.bodySmall?.copyWith(
            color: theme.textTheme.bodySmall?.color?.withOpacity(0.7),
          ),
        ),
      ],
    );
  }

  /// Campo:  Usuário
  Widget _buildUserField(Color borderColor, bool isDark) {
    return TextFormField(
      controller: _userCtrl,
      focusNode: _userFocus,
      textInputAction: TextInputAction.next,
      keyboardType: TextInputType.text,
      onFieldSubmitted: (_) => _passFocus.requestFocus(),
      decoration: _buildInputDecoration(
        label: 'Usuário *',
        icon: Icons.person_outline,
        borderColor: borderColor,
        isDark: isDark,
      ),
      validator: _validateUser,
    );
  }

  /// Campo: Senha
  Widget _buildPasswordField(Color borderColor, bool isDark) {
    return TextFormField(
      controller: _passCtrl,
      focusNode: _passFocus,
      obscureText: _obscurePassword,
      textInputAction: TextInputAction.done,
      onFieldSubmitted: (_) => _submitForm(),
      decoration: _buildInputDecoration(
        label: 'Senha *',
        icon: Icons.lock_outline,
        borderColor: borderColor,
        isDark: isDark,
        suffixIcon: IconButton(
          icon: Icon(
            _obscurePassword ? Icons.visibility_off : Icons.visibility,
            color: borderColor.withOpacity(0.6),
          ),
          onPressed: () {
            setState(() => _obscurePassword = !_obscurePassword);
          },
        ),
      ),
      validator: _validatePassword,
    );
  }

  /// Link:  Esqueceu a senha?
  Widget _buildForgotPasswordLink(ThemeData theme, bool isDark) {
    return Align(
      alignment: Alignment.centerRight,
      child: TextButton(
        onPressed: () {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Recuperação de senha (não implementada)'),
            ),
          );
        },
        style: TextButton.styleFrom(
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
          minimumSize: Size.zero,
          tapTargetSize: MaterialTapTargetSize.shrinkWrap,
        ),
        child: Text(
          'Esqueceu a senha? ',
          style: theme.textTheme.bodySmall?.copyWith(
            color:
                isDark ? Colors.purpleAccent.shade100 : Colors.indigo.shade700,
            fontWeight: FontWeight.w500,
          ),
        ),
      ),
    );
  }

  /// Botão: Entrar
  Widget _buildSubmitButton(ThemeData theme, Color buttonColor, bool isDark) {
    return SizedBox(
      width: double.infinity,
      child: ElevatedButton(
        onPressed: _isSubmitting ? null : _submitForm,
        style: ElevatedButton.styleFrom(
          backgroundColor: buttonColor,
          foregroundColor: isDark ? Colors.black87 : Colors.white,
          padding: const EdgeInsets.symmetric(vertical: 14),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(14),
          ),
          elevation: 8,
        ),
        child:
            _isSubmitting
                ? const SizedBox(
                  width: 20,
                  height: 20,
                  child: CircularProgressIndicator(
                    strokeWidth: 2,
                    color: Colors.white,
                  ),
                )
                : Text(
                  'Entrar',
                  style: theme.textTheme.titleMedium?.copyWith(
                    color: isDark ? Colors.black87 : Colors.white,
                    fontWeight: FontWeight.w600,
                  ),
                ),
      ),
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // ESPAÇADORES
  // ══════════════════════════════════════════════════════════════════════════
  Widget get _fieldSpacer => const SizedBox(height: 12);
  Widget get _sectionSpacer => const SizedBox(height: 16);
  Widget get _smallSpacer => const SizedBox(height: 8);

  // ══════════════════════════════════════════════════════════════════════════
  // BUILD PRINCIPAL
  // ══════════════════════════════════════════════════════════════════════════
  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;
    final borderColor = isDark ? Colors.white : Colors.black;
    final buttonColor =
        isDark ? Colors.purpleAccent.shade100 : Colors.indigo.shade900;

    return Center(
      child: ConstrainedBox(
        constraints: const BoxConstraints(maxWidth: _maxWidth),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            // ─────────────────────────────────────────────────────────────────
            // ÍCONE CADEADO (FORA DO CARD, ACIMA)
            // ─────────────────────────────────────────────────────────────────
            _buildLockIcon(isDark),
            const SizedBox(height: _iconSpacing), // ← USA A CONFIGURAÇÃO AQUI
            // ─────────────────────────────────────────────────────────────────
            // CARD DO FORMULÁRIO
            // ─────────────────────────────────────────────────────────────────
            Container(
              padding: const EdgeInsets.all(18),
              decoration: _buildContainerDecoration(isDark, borderColor),
              child: Form(
                key: _formKey,
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    // ─────────────────────────────────────────────────────────
                    // HEADER
                    // ─────────────────────────────────────────────────────────
                    _buildHeader(theme),
                    _sectionSpacer,

                    // ─────────────────────────────────────────────────────────
                    // CAMPO USUÁRIO
                    // ─────────────────────────────────────────────────────────
                    _buildUserField(borderColor, isDark),
                    _fieldSpacer,

                    // ─────────────────────────────────────────────────────────
                    // CAMPO SENHA
                    // ─────────────────────────────────────────────────────────
                    _buildPasswordField(borderColor, isDark),
                    _smallSpacer,

                    // ─────────────────────────────────────────────────────────
                    // LINK ESQUECEU SENHA
                    // ─────────────────────────────────────────────────────────
                    _buildForgotPasswordLink(theme, isDark),
                    _fieldSpacer,

                    // ─────────────────────────────────────────────────────────
                    // BOTÃO ENTRAR
                    // ─────────────────────────────────────────────────────────
                    _buildSubmitButton(theme, buttonColor, isDark),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
