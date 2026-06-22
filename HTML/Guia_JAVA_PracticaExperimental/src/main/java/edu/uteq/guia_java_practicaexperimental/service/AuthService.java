package edu.uteq.guia_java_practicaexperimental.service;

import edu.uteq.guia_java_practicaexperimental.dto.request.LoginRequest;
import edu.uteq.guia_java_practicaexperimental.dto.request.RegisterRequest;
import edu.uteq.guia_java_practicaexperimental.dto.response.AuthResponse;

public interface AuthService {
    AuthResponse login(LoginRequest request);
    AuthResponse register(RegisterRequest request);
}
