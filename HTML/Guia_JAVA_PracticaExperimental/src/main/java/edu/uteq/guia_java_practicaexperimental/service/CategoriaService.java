package edu.uteq.guia_java_practicaexperimental.service;

import edu.uteq.guia_java_practicaexperimental.dto.request.CategoriaRequest;
import edu.uteq.guia_java_practicaexperimental.dto.response.CategoriaResponse;

import java.util.List;

public interface CategoriaService {
    List<CategoriaResponse> findAll();
    CategoriaResponse findById(Long id);
    CategoriaResponse save(CategoriaRequest request);
    CategoriaResponse update(Long id, CategoriaRequest request);
    void delete(Long id);
    List<CategoriaResponse> buscarPorNombre(String nombre);
}
