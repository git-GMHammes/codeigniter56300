import '../../app/config/env.dart';

class HttpClient {
  final String baseUrl;

  HttpClient({String? baseUrl}) : baseUrl = baseUrl ?? Env.apiBaseUrl;

  // TODO: implementar requests (http/dio)
}
