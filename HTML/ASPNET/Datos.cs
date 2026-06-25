using Microsoft.EntityFrameworkCore;
using System.Text.Json.Serialization;

// Entidades
public class Carrera
{
    public int Id { get; set; }
    public string Nombre { get; set; } = "";

    [JsonIgnore]
    public List<Estudiante> Estudiantes { get; set; } = new();
}

public class Estudiante
{
    public int Id { get; set; }
    public string Nombre { get; set; } = "";
    public string Correo { get; set; } = "";
    
    public int CarreraId { get; set; }
    public Carrera? Carrera { get; set; }
    
    public DateTime Creado { get; set; }
}

public class Curso
{
    public int Id { get; set; }
    public string Nombre { get; set; } = "";
}

// DTOs
public record EstudianteEntradaDto(
    string Nombre,
    string Correo,
    int CarreraId
);

public record EstudianteSalidaDto(
    int Id,
    string Nombre,
    string Correo,
    string CarreraNombre,
    DateTime Creado
);

public record CarreraEntradaDto(string Nombre);
public record CarreraSalidaDto(int Id, string Nombre);

public record LoginDto(string Usuario, string Clave);

// DbContext
public class AppDbContext : DbContext
{
    public AppDbContext(DbContextOptions<AppDbContext> options)
        : base(options)
    {
    }

    public DbSet<Estudiante> Estudiantes => Set<Estudiante>();
    public DbSet<Carrera> Carreras => Set<Carrera>();
    public DbSet<Curso> Cursos => Set<Curso>();
}

// Validadores simples
public static class ValidadorEstudiante
{
    public static Dictionary<string, string[]> Validar(EstudianteEntradaDto e)
    {
        var errores = new Dictionary<string, string[]>();

        if (string.IsNullOrWhiteSpace(e.Nombre))
            errores["nombre"] = new[] { "El nombre es obligatorio." };

        if (string.IsNullOrWhiteSpace(e.Correo) || !e.Correo.Contains('@'))
            errores["correo"] = new[] { "El correo no es válido." };

        if (e.CarreraId <= 0)
            errores["carreraId"] = new[] { "La carrera es obligatoria." };

        return errores;
    }
}