import 'package:flutter/material.dart';
import '../../domain/entities/contact_us_item.dart';

class ContactUsFormCard extends StatefulWidget {
  final Function(ContactUsItem)? onSubmit;

  const ContactUsFormCard({super.key, this.onSubmit});

  @override
  State<ContactUsFormCard> createState() => _ContactUsFormCardState();
}

class _ContactUsFormCardState extends State<ContactUsFormCard> {
  // ══════════════════════════════════════════════════════════════════════════
  // CONTROLLERS E FOCUS NODES
  // ══════════════════════════════════════════════════════════════════════════
  final _formKey = GlobalKey<FormState>();
  final _nameCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _subjectCtrl = TextEditingController();
  final _messageCtrl = TextEditingController();

  final _nameFocus = FocusNode();
  final _emailFocus = FocusNode();
  final _subjectFocus = FocusNode();
  final _messageFocus = FocusNode();

  String? _selectedCategory;
  bool _isSubmitting = false;

  // ══════════════════════════════════════════════════════════════════════════
  // LIFECYCLE
  // ══════════════════════════════════════════════════════════════════════════
  @override
  void dispose() {
    _nameCtrl.dispose();
    _emailCtrl.dispose();
    _subjectCtrl.dispose();
    _messageCtrl.dispose();
    _nameFocus.dispose();
    _emailFocus.dispose();
    _subjectFocus.dispose();
    _messageFocus.dispose();
    super.dispose();
  }

  // ══════════════════════════════════════════════════════════════════════════
  // SUBMIT
  // ══════════════════════════════════════════════════════════════════════════
  void _submitForm() {
    if (_formKey.currentState?.validate() ?? false) {
      setState(() => _isSubmitting = true);

      final contactItem = ContactUsItem(
        name: _nameCtrl.text.trim(),
        email: _emailCtrl.text.trim(),
        category: _selectedCategory ?? '',
        subject: _subjectCtrl.text.trim(),
        message: _messageCtrl.text.trim(),
        status: 'pending',
      );

      if (widget.onSubmit != null) {
        widget.onSubmit!(contactItem);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Mensagem enviada com sucesso!'),
            backgroundColor: Colors.green,
          ),
        );
      }

      _clearForm();
    }
  }

  void _clearForm() {
    _formKey.currentState?.reset();
    _nameCtrl.clear();
    _emailCtrl.clear();
    _subjectCtrl.clear();
    _messageCtrl.clear();
    setState(() {
      _selectedCategory = null;
      _isSubmitting = false;
    });
  }

  // ══════════════════════════════════════════════════════════════════════════
  // VALIDATORS
  // ══════════════════════════════════════════════════════════════════════════
  String? _validateName(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Nome é obrigatório';
    }
    if (value.trim().length < 3) {
      return 'Nome deve ter pelo menos 3 caracteres';
    }
    return null;
  }

  String? _validateEmail(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'E-mail é obrigatório';
    }
    final emailRegex = RegExp(
      r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$',
    );
    if (!emailRegex.hasMatch(value.trim())) {
      return 'E-mail inválido';
    }
    return null;
  }

  String? _validateCategory(String? value) {
    if (value == null || value.isEmpty) {
      return 'Selecione uma categoria';
    }
    return null;
  }

  String? _validateSubject(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Assunto é obrigatório';
    }
    if (value.trim().length < 5) {
      return 'Assunto deve ter pelo menos 5 caracteres';
    }
    return null;
  }

  String? _validateMessage(String? value) {
    if (value == null || value.trim().isEmpty) {
      return 'Mensagem é obrigatória';
    }
    if (value.trim().length < 10) {
      return 'Mensagem deve ter pelo menos 10 caracteres';
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
    bool alignLabelWithHint = false,
  }) {
    return InputDecoration(
      labelText: label,
      prefixIcon: Icon(icon),
      floatingLabelBehavior: FloatingLabelBehavior.auto,
      alignLabelWithHint: alignLabelWithHint,
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
  // FIELD BUILDERS - Cada campo em seu próprio método
  // ══════════════════════════════════════════════════════════════════════════

  /// Campo:  Nome completo
  Widget _buildNameField(Color borderColor, bool isDark) {
    return TextFormField(
      controller: _nameCtrl,
      focusNode: _nameFocus,
      textInputAction: TextInputAction.next,
      onFieldSubmitted: (_) => _emailFocus.requestFocus(),
      decoration: _buildInputDecoration(
        label: 'Nome completo *',
        icon: Icons.person_outline,
        borderColor: borderColor,
        isDark: isDark,
      ),
      validator: _validateName,
    );
  }

  /// Campo: E-mail
  Widget _buildEmailField(Color borderColor, bool isDark) {
    return TextFormField(
      controller: _emailCtrl,
      focusNode: _emailFocus,
      textInputAction: TextInputAction.next,
      keyboardType: TextInputType.emailAddress,
      onFieldSubmitted: (_) => _subjectFocus.requestFocus(),
      decoration: _buildInputDecoration(
        label: 'E-mail *',
        icon: Icons.email_outlined,
        borderColor: borderColor,
        isDark: isDark,
      ),
      validator: _validateEmail,
    );
  }

  /// Campo: Categoria (Dropdown)
  Widget _buildCategoryField(Color borderColor, bool isDark) {
    return DropdownButtonFormField<String>(
      value: _selectedCategory,
      decoration: _buildInputDecoration(
        label: 'Categoria *',
        icon: Icons.category_outlined,
        borderColor: borderColor,
        isDark: isDark,
      ),
      items:
          ContactUsItem.categoryOptions.map((category) {
            return DropdownMenuItem<String>(
              value: category,
              child: Text(category),
            );
          }).toList(),
      onChanged: (value) {
        setState(() => _selectedCategory = value);
      },
      validator: _validateCategory,
    );
  }

  /// Campo: Assunto
  Widget _buildSubjectField(Color borderColor, bool isDark) {
    return TextFormField(
      controller: _subjectCtrl,
      focusNode: _subjectFocus,
      textInputAction: TextInputAction.next,
      onFieldSubmitted: (_) => _messageFocus.requestFocus(),
      decoration: _buildInputDecoration(
        label: 'Assunto *',
        icon: Icons.subject_outlined,
        borderColor: borderColor,
        isDark: isDark,
      ),
      validator: _validateSubject,
    );
  }

  /// Campo: Mensagem
  Widget _buildMessageField(Color borderColor, bool isDark) {
    return TextFormField(
      controller: _messageCtrl,
      focusNode: _messageFocus,
      textInputAction: TextInputAction.newline,
      keyboardType: TextInputType.multiline,
      maxLines: 5,
      minLines: 3,
      decoration: _buildInputDecoration(
        label: 'Mensagem *',
        icon: Icons.message_outlined,
        borderColor: borderColor,
        isDark: isDark,
        alignLabelWithHint: true,
      ),
      validator: _validateMessage,
    );
  }

  /// Botão:  Enviar
  Widget _buildSubmitButton(ThemeData theme, Color buttonColor, bool isDark) {
    return SizedBox(
      width: double.infinity,
      child: ElevatedButton.icon(
        onPressed: _isSubmitting ? null : _submitForm,
        icon:
            _isSubmitting
                ? const SizedBox(
                  width: 20,
                  height: 20,
                  child: CircularProgressIndicator(
                    strokeWidth: 2,
                    color: Colors.white,
                  ),
                )
                : const Icon(Icons.send_outlined),
        label: Text(
          _isSubmitting ? 'Enviando.. .' : 'Enviar Mensagem',
          style: theme.textTheme.titleMedium?.copyWith(
            color: isDark ? Colors.black87 : Colors.white,
            fontWeight: FontWeight.w600,
          ),
        ),
        style: ElevatedButton.styleFrom(
          backgroundColor: buttonColor,
          foregroundColor: isDark ? Colors.black87 : Colors.white,
          padding: const EdgeInsets.symmetric(vertical: 14),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(14),
          ),
          elevation: 8,
        ),
      ),
    );
  }

  // ══════════════════════════════════════════════════════════════════════════
  // HEADER BUILDER
  // ══════════════════════════════════════════════════════════════════════════
  Widget _buildHeader(ThemeData theme) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'Fale Conosco',
          style: theme.textTheme.titleLarge?.copyWith(
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 4),
        Text(
          'Preencha o formulário abaixo para entrar em contato',
          style: theme.textTheme.bodySmall?.copyWith(
            color: theme.textTheme.bodySmall?.color?.withOpacity(0.7),
          ),
        ),
      ],
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
  // ESPAÇADOR PADRÃO
  // ══════════════════════════════════════════════════════════════════════════
  Widget get _fieldSpacer => const SizedBox(height: 12);
  Widget get _sectionSpacer => const SizedBox(height: 16);

  // ══════════════════════════════════════════════════════════════════════════
  // BUILD PRINCIPAL - Apenas renderiza os campos
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
        constraints: const BoxConstraints(maxWidth: 600),
        child: Container(
          padding: const EdgeInsets.all(18),
          decoration: _buildContainerDecoration(isDark, borderColor),
          child: Form(
            key: _formKey,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                // ─────────────────────────────────────────────────────────────
                // HEADER
                // ─────────────────────────────────────────────────────────────
                _buildHeader(theme),
                _sectionSpacer,

                // ─────────────────────────────────────────────────────────────
                // CAMPOS DO FORMULÁRIO
                // ─────────────────────────────────────────────────────────────
                _buildNameField(borderColor, isDark),
                _fieldSpacer,

                _buildEmailField(borderColor, isDark),
                _fieldSpacer,

                _buildCategoryField(borderColor, isDark),
                _fieldSpacer,

                _buildSubjectField(borderColor, isDark),
                _fieldSpacer,

                _buildMessageField(borderColor, isDark),
                _sectionSpacer,

                // ─────────────────────────────────────────────────────────────
                // BOTÃO ENVIAR
                // ─────────────────────────────────────────────────────────────
                _buildSubmitButton(theme, buttonColor, isDark),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
