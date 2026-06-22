using Microsoft.EntityFrameworkCore;

// Entidad
public class Estudiante
{
    public int Id { get; set; }
    public string Nombre { get; set; } = "";
    public string Correo { get; set; } = "";
    public string Carrera { get; set; } = "";
    public DateTime Creado { get; set; }
}

// DTO: lo que el cliente envía
public record EstudianteEntradaDto(
    string Nombre,
    string Correo,
    string Carrera
);

// DTO: lo que la API devuelve
public record EstudianteSalidaDto(
    int Id,
    string Nombre,
    string Correo,
    string Carrera,
    DateTime Creado
);

// DbContext
public class AppDbContext : DbContext
{
    public AppDbContext(DbContextOptions<AppDbContext> options)
        : base(options)
    {
    }

    public DbSet<Estudiante> Estudiantes => Set<Estudiante>();
}

public static class ValidadorEstudiante
{
    public static Dictionary<string, string[]> Validar(EstudianteEntradaDto e)
    {
        var errores = new Dictionary<string, string[]>();

        if (string.IsNullOrWhiteSpace(e.Nombre))
            errores["nombre"] = new[] { "El nombre es obligatorio." };

        if (string.IsNullOrWhiteSpace(e.Correo) || !e.Correo.Contains('@'))
            errores["correo"] = new[] { "El correo no es válido." };

        if (string.IsNullOrWhiteSpace(e.Carrera))
            errores["carrera"] = new[] { "La carrera es obligatoria." };

        return errores;
    }
}