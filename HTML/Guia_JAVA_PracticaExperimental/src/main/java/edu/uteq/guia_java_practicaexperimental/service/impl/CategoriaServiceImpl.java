package edu.uteq.guia_java_practicaexperimental.service.impl;

import edu.uteq.guia_java_practicaexperimental.dto.request.CategoriaRequest;
import edu.uteq.guia_java_practicaexperimental.dto.response.CategoriaResponse;
import edu.uteq.guia_java_practicaexperimental.exception.BadRequestException;
import edu.uteq.guia_java_practicaexperimental.exception.ResourceNotFoundException;
import edu.uteq.guia_java_practicaexperimental.model.Categoria;
import edu.uteq.guia_java_practicaexperimental.repository.CategoriaRepository;
import edu.uteq.guia_java_practicaexperimental.service.CategoriaService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.stream.Collectors;

@Service
public class CategoriaServiceImpl implements CategoriaService {

    @Autowired
    private CategoriaRepository categoriaRepository;

    private CategoriaResponse mapToResponse(Categoria categoria) {
        return CategoriaResponse.builder()
                .id(categoria.getId())
                .nombre(categoria.getNombre())
                .descripcion(categoria.getDescripcion())
                .build();
    }

    @Override
    @Transactional(readOnly = true)
    public List<CategoriaResponse> findAll() {
        return categoriaRepository.findAll().stream()
                .map(this::mapToResponse)
                .collect(Collectors.toList());
    }

    @Override
    @Transactional(readOnly = true)
    public CategoriaResponse findById(Long id) {
        Categoria categoria = categoriaRepository.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Categoria", "id", id));
        return mapToResponse(categoria);
    }

    @Override
    @Transactional
    public CategoriaResponse save(CategoriaRequest request) {
        if (categoriaRepository.existsByNombreIgnoreCase(request.getNombre())) {
            throw new BadRequestException("La categoría '" + request.getNombre() + "' ya existe.");
        }

        Categoria categoria = Categoria.builder()
                .nombre(request.getNombre())
                .descripcion(request.getDescripcion())
                .build();

        Categoria savedCategoria = categoriaRepository.save(categoria);
        return mapToResponse(savedCategoria);
    }

    @Override
    @Transactional
    public CategoriaResponse update(Long id, CategoriaRequest request) {
        Categoria categoria = categoriaRepository.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Categoria", "id", id));

        if (!categoria.getNombre().equalsIgnoreCase(request.getNombre()) &&
                categoriaRepository.existsByNombreIgnoreCase(request.getNombre())) {
            throw new BadRequestException("La categoría '" + request.getNombre() + "' ya existe.");
        }

        categoria.setNombre(request.getNombre());
        categoria.setDescripcion(request.getDescripcion());

        Categoria updatedCategoria = categoriaRepository.save(categoria);
        return mapToResponse(updatedCategoria);
    }

    @Override
    @Transactional
    public void delete(Long id) {
        Categoria categoria = categoriaRepository.findById(id)
                .orElseThrow(() -> new ResourceNotFoundException("Categoria", "id", id));
        categoriaRepository.delete(categoria);
    }

    @Override
    @Transactional(readOnly = true)
    public List<CategoriaResponse> buscarPorNombre(String nombre) {
        return categoriaRepository.findByNombreContainingIgnoreCase(nombre).stream()
                .map(this::mapToResponse)
                .collect(Collectors.toList());
    }
}
