using Microsoft.EntityFrameworkCore;

public interface IEstudianteService
{
    Task<(IEnumerable<EstudianteSalidaDto> datos, int total)> ObtenerTodosAsync(int pagina, int tamano, string? orden, string? buscar);
    Task<EstudianteSalidaDto?> ObtenerPorIdAsync(int id);
    Task<(EstudianteSalidaDto? estudiante, Dictionary<string, string[]>? errores)> CrearAsync(EstudianteEntradaDto dto);
    Task<Dictionary<string, string[]>?> ActualizarAsync(int id, EstudianteEntradaDto dto);
    Task<bool> EliminarAsync(int id);
}

public class EstudianteService : IEstudianteService
{
    private readonly AppDbContext _db;

    public EstudianteService(AppDbContext db)
    {
        _db = db;
    }

    public async Task<(IEnumerable<EstudianteSalidaDto> datos, int total)> ObtenerTodosAsync(int pagina, int tamano, string? orden, string? buscar)
    {
        if (pagina < 1) pagina = 1;
        if (tamano < 1 || tamano > 100) tamano = 10;

        var query = _db.Estudiantes.Include(e => e.Carrera).AsQueryable();

        if (!string.IsNullOrWhiteSpace(buscar))
        {
            query = query.Where(e => e.Nombre.Contains(buscar) || e.Carrera!.Nombre.Contains(buscar));
        }

        // Para orden dinámico en BD usaremos un stored procedure como pidió el usuario.
        // Como EF Core requiere mapear a entidades o usar FromSqlRaw, simulamos el orden localmente o con SP.
        // "mejor crea procedimiento almacenados y ejecutalos durante la misgracion"
        // Llamaremos al SP 'OrdenarEstudiantes'
        
        string sql = "SELECT * FROM ordenar_estudiantes({0}, {1}, {2}, {3})";
        // Si PostgreSQL, function returns set of records.
        // Para simplificar, la función ordenar_estudiantes devolverá id, nombre, correo, carrera_id, creado.
        // Pero EF requiere que se devuelvan todas las columnas para mapear.
        
        // Wait, to keep things simple with EF Core 8/9/10 and SPs mapping to Entities + Includes,
        // it's easier to just do order by string here unless we strictly use FromSql.
        // We will implement the SP approach shortly.
        var datos = await _db.Estudiantes
            .FromSqlRaw("SELECT * FROM obtener_estudiantes_paginados({0}, {1}, {2}, {3})", pagina, tamano, orden ?? "id", buscar ?? "")
            .Include(e => e.Carrera)
            .Select(e => new EstudianteSalidaDto(e.Id, e.Nombre, e.Correo, e.Carrera != null ? e.Carrera.Nombre : "", e.Creado))
            .ToListAsync();

        // Count for total
        int total = await query.CountAsync();

        return (datos, total);
    }

    public async Task<EstudianteSalidaDto?> ObtenerPorIdAsync(int id)
    {
        var e = await _db.Estudiantes.Include(est => est.Carrera).FirstOrDefaultAsync(est => est.Id == id);
        if (e is null) return null;
        return new EstudianteSalidaDto(e.Id, e.Nombre, e.Correo, e.Carrera?.Nombre ?? "", e.Creado);
    }

    public async Task<(EstudianteSalidaDto? estudiante, Dictionary<string, string[]>? errores)> CrearAsync(EstudianteEntradaDto dto)
    {
        var errores = ValidadorEstudiante.Validar(dto);
        if (errores.Count > 0) return (null, errores);

        // Validar correo único (ejercicio 13.2.3)
        if (await _db.Estudiantes.AnyAsync(e => e.Correo == dto.Correo))
        {
            errores["correo"] = new[] { "El correo ya está registrado." };
            return (null, errores);
        }

        var carrera = await _db.Carreras.FindAsync(dto.CarreraId);
        if (carrera == null)
        {
             errores["carreraId"] = new[] { "La carrera especificada no existe." };
             return (null, errores);
        }

        var e = new Estudiante
        {
            Nombre = dto.Nombre,
            Correo = dto.Correo,
            CarreraId = dto.CarreraId,
            Creado = DateTime.UtcNow
        };

        _db.Estudiantes.Add(e);
        await _db.SaveChangesAsync();

        return (new EstudianteSalidaDto(e.Id, e.Nombre, e.Correo, carrera.Nombre, e.Creado), null);
    }

    public async Task<Dictionary<string, string[]>?> ActualizarAsync(int id, EstudianteEntradaDto dto)
    {
        var errores = ValidadorEstudiante.Validar(dto);
        if (errores.Count > 0) return errores;

        var e = await _db.Estudiantes.FindAsync(id);
        if (e is null) return new Dictionary<string, string[]> { { "id", new[] { "No encontrado" } } };

        if (e.Correo != dto.Correo && await _db.Estudiantes.AnyAsync(est => est.Correo == dto.Correo))
        {
            errores["correo"] = new[] { "El correo ya está registrado." };
            return errores;
        }

        e.Nombre = dto.Nombre;
        e.Correo = dto.Correo;
        e.CarreraId = dto.CarreraId;

        await _db.SaveChangesAsync();
        return null; // Éxito
    }

    public async Task<bool> EliminarAsync(int id)
    {
        var e = await _db.Estudiantes.FindAsync(id);
        if (e is null) return false;

        _db.Estudiantes.Remove(e);
        await _db.SaveChangesAsync();
        return true;
    }
}
