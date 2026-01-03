import 'package:flutter/material.dart';

/// Widget para a Etapa 1 do cadastro:  Usuário e Senha
class RegisterStep1Card extends StatefulWidget {
  final String initialUser;
  final String initialPassword;
  final void Function(String user, String password) onComplete;

  const RegisterStep1Card({
    super.key,
    this.initialUser = '',
    this.initialPassword = '',
    required this.onComplete,
  });

  @override
  State<RegisterStep1Card> createState() => _RegisterStep1CardState();
}

class _RegisterStep1CardState extends State<RegisterStep1Card> {
  // ══════════════════════════════════════════════════════════════════════════
  // CONTROLLERS & STATE
  // ══════════════════════════════════════════════════════════════════════════
  final _formKey = GlobalKey<FormState>();
  late final TextEditingController _userController;
  late final TextEditingController _passwordController;
  late final TextEditingController _confirmPasswordController;

  bool _obscurePassword = true;
  bool _obscureConfirmPassword = true;
  bool _isLoading = false;

  // ══════════════════════════════════════════════════════════════════════════
  // LIFECYCLE
  // ══════════════════════════════════════════════════════════════════════════
  @override
  void initState() {
    super.initState();
    _userController = TextEditingController(text: widget.initialUser);
    _passwordController = TextEditingController(text: widget.initialPassword);
    _confirmPasswordController = TextEditingController(
      text: widget.initialPassword,
    );
  }

  @override
  void dispose() {
    _userController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  // ══════════════════════════════════════════════════════════════════════════
  // VALIDATORS
  // ══════════════════════════════════════════════════════════════════════════
  String? _validateUser(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Informe o usuário';
    }
    if (value.trim().length < 4) {
      return 'Usuário deve ter pelo menos 4 caracteres';
    }
    if (value.trim().length > 50) {
      return 'Usuário deve ter no máximo 50 caracteres';
    }
    // Verifica se contém apenas caracteres válidos
    final validChars = RegExp(r'^[a-zA-Z0-9._@]+$');
    if (!validChars.hasMatch(value.trim())) {
      return 'Use apenas letras, números, ponto, underline ou @';
    }
    return null;
  }

  String? _validatePassword(String? value) {
    if (value == null || value.isEmpty) {
      return 'Informe a senha';
    }
    if (value.length < 6) {
      return 'Senha deve ter pelo menos 6 caracteres';
    }
    if (value.length > 200) {
      return 'Senha muito longa';
    }
    return null;
  }

  String? _validateConfirmPassword(String? value) {
    if (value == null || value.isEmpty) {
      return 'Confirme a senha';
    }
    if (value != _passwordController.text) {
      return 'As senhas não conferem';
    }
    return null;
  }

  // ══════════════════════════════════════════════════════════════════════════
  // ACTIONS
  // ══════════════════════════════════════════════════════════════════════════
  Future<void> _handleNext() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isLoading = true);

    // Simula verificação de disponibilidade do usuário
    await Future.delayed(const Duration(milliseconds: 500));

    if (mounted) {
      setState(() => _isLoading = false);
      widget.onComplete(_userController.text.trim(), _passwordController.text);
    }
  }

  // ══════════════════════════════════════════════════════════════════════════
  // BUILDERS
  // ══════════════════════════════════════════════════════════════════════════

  /// Ícone principal do cadastro
  Widget _buildIcon(ThemeData theme, bool isDark) {
    final iconColor = isDark ? Colors.purpleAccent : Colors.indigo;

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: iconColor.withOpacity(0.1),
        shape: BoxShape.circle,
      ),
      child: Icon(Icons.person_add_alt_1_rounded, size: 48, color: iconColor),
    );
  }

  /// Campo de usuário
  Widget _buildUserField(ThemeData theme, bool isDark) {
    return TextFormField(
      controller: _userController,
      validator: _validateUser,
      textInputAction: TextInputAction.next,
      keyboardType: TextInputType.text,
      autocorrect: false,
      enableSuggestions: false,
      decoration: InputDecoration(
        labelText: 'Usuário *',
        hintText: 'Digite seu usuário ou e-mail',
        prefixIcon: const Icon(Icons.person_outline),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
        filled: true,
        fillColor: isDark ? Colors.grey.shade900 : Colors.grey.shade50,
      ),
    );
  }

  /// Campo de senha
  Widget _buildPasswordField(ThemeData theme, bool isDark) {
    return TextFormField(
      controller: _passwordController,
      validator: _validatePassword,
      obscureText: _obscurePassword,
      textInputAction: TextInputAction.next,
      decoration: InputDecoration(
        labelText: 'Senha *',
        hintText: 'Mínimo 6 caracteres',
        prefixIcon: const Icon(Icons.lock_outline),
        suffixIcon: IconButton(
          icon: Icon(
            _obscurePassword ? Icons.visibility_off : Icons.visibility,
          ),
          onPressed: () => setState(() => _obscurePassword = !_obscurePassword),
        ),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
        filled: true,
        fillColor: isDark ? Colors.grey.shade900 : Colors.grey.shade50,
      ),
    );
  }

  /// Campo de confirmação de senha
  Widget _buildConfirmPasswordField(ThemeData theme, bool isDark) {
    return TextFormField(
      controller: _confirmPasswordController,
      validator: _validateConfirmPassword,
      obscureText: _obscureConfirmPassword,
      textInputAction: TextInputAction.done,
      onFieldSubmitted: (_) => _handleNext(),
      decoration: InputDecoration(
        labelText: 'Confirmar Senha *',
        hintText: 'Repita a senha',
        prefixIcon: const Icon(Icons.lock_outline),
        suffixIcon: IconButton(
          icon: Icon(
            _obscureConfirmPassword ? Icons.visibility_off : Icons.visibility,
          ),
          onPressed:
              () => setState(
                () => _obscureConfirmPassword = !_obscureConfirmPassword,
              ),
        ),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
        filled: true,
        fillColor: isDark ? Colors.grey.shade900 : Colors.grey.shade50,
      ),
    );
  }

  /// Botão de próximo
  Widget _buildNextButton(ThemeData theme, bool isDark) {
    return SizedBox(
      width: double.infinity,
      height: 52,
      child: FilledButton.icon(
        onPressed: _isLoading ? null : _handleNext,
        icon:
            _isLoading
                ? SizedBox(
                  width: 20,
                  height: 20,
                  child: CircularProgressIndicator(
                    strokeWidth: 2,
                    color: isDark ? Colors.white70 : Colors.white,
                  ),
                )
                : const Icon(Icons.arrow_forward),
        label: Text(
          _isLoading ? 'Verificando...' : 'Próximo',
          style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w600),
        ),
        style: FilledButton.styleFrom(
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(12),
          ),
        ),
      ),
    );
  }

  /// Dicas de segurança
  Widget _buildSecurityTips(ThemeData theme, bool isDark) {
    final tipColor = isDark ? Colors.grey.shade400 : Colors.grey.shade600;

    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color:
            isDark
                ? Colors.grey.shade800.withOpacity(0.5)
                : Colors.blue.shade50,
        borderRadius: BorderRadius.circular(8),
        border: Border.all(
          color: isDark ? Colors.grey.shade700 : Colors.blue.shade100,
        ),
      ),
      child: Row(
        children: [
          Icon(
            Icons.info_outline,
            size: 20,
            color: isDark ? Colors.blue.shade300 : Colors.blue.shade700,
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              'Use uma senha forte com letras, números e caracteres especiais.',
              style: TextStyle(fontSize: 12, color: tipColor),
            ),
          ),
        ],
      ),
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // BUILD PRINCIPAL
  // ══════════════════════════════════════════════════════════════════════════
  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final isDark = theme.brightness == Brightness.dark;

    return Card(
      elevation: isDark ? 2 : 4,
      shadowColor: isDark ? Colors.black45 : Colors.black26,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              // ─────────────────────────────────────────────────────────────────
              // ÍCONE
              // ─────────────────────────────────────────────────────────────────
              _buildIcon(theme, isDark),
              const SizedBox(height: 20),

              // ─────────────────────────────────────────────────────────────────
              // TÍTULO
              // ─────────────────────────────────────────────────────────────────
              Text(
                'Criar Conta',
                style: theme.textTheme.headlineSmall?.copyWith(
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                'Etapa 1: Dados de acesso',
                style: theme.textTheme.bodyMedium?.copyWith(
                  color: isDark ? Colors.grey.shade400 : Colors.grey.shade600,
                ),
              ),
              const SizedBox(height: 24),

              // ─────────────────────────────────────────────────────────────────
              // CAMPO USUÁRIO
              // ─────────────────────────────────────────────────────────────────
              _buildUserField(theme, isDark),
              const SizedBox(height: 16),

              // ─────────────────────────────────────────────────────────────────
              // CAMPO SENHA
              // ─────────────────────────────────────────────────────────────────
              _buildPasswordField(theme, isDark),
              const SizedBox(height: 16),

              // ─────────────────────────────────────────────────────────────────
              // CAMPO CONFIRMAR SENHA
              // ─────────────────────────────────────────────────────────────────
              _buildConfirmPasswordField(theme, isDark),
              const SizedBox(height: 20),

              // ─────────────────────────────────────────────────────────────────
              // DICAS DE SEGURANÇA
              // ─────────────────────────────────────────────────────────────────
              _buildSecurityTips(theme, isDark),
              const SizedBox(height: 24),

              // ─────────────────────────────────────────────────────────────────
              // BOTÃO PRÓXIMO
              // ─────────────────────────────────────────────────────────────────
              _buildNextButton(theme, isDark),
            ],
          ),
        ),
      ),
    );
  }
}
