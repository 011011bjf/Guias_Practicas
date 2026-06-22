using Microsoft.EntityFrameworkCore;
using Scalar.AspNetCore;

var builder = WebApplication.CreateBuilder(args);

// DbContext PostgreSQL
builder.Services.AddDbContext<AppDbContext>(options =>
    options.UseNpgsql(
        builder.Configuration.GetConnectionString("Postgres"),
        npg => npg.SetPostgresVersion(16, 0)
    )
);

// OpenAPI
builder.Services.AddOpenApi();

var app = builder.Build();

// OpenAPI solo en desarrollo
if (app.Environment.IsDevelopment())
{
    app.MapOpenApi();
    app.MapScalarApiReference();
}

app.UseHttpsRedirection();

// =========================
// ENDPOINTS CRUD
// =========================

var grupo = app.MapGroup("/v1/estudiantes");

// READ: todos
grupo.MapGet("/", async (AppDbContext db) =>
    await db.Estudiantes
        .OrderBy(e => e.Id)
        .Select(e => new EstudianteSalidaDto(
            e.Id, e.Nombre, e.Correo, e.Carrera, e.Creado))
        .ToListAsync()
);

// READ: por id
grupo.MapGet("/{id:int}", async (int id, AppDbContext db) =>
{
    var e = await db.Estudiantes.FindAsync(id);

    return e is null
        ? Results.NotFound()
        : Results.Ok(new EstudianteSalidaDto(
            e.Id, e.Nombre, e.Correo, e.Carrera, e.Creado));
});

// CREATE
grupo.MapPost("/", async (EstudianteEntradaDto dto, AppDbContext db) =>
{
    var errores = ValidadorEstudiante.Validar(dto);
    if (errores.Count > 0)
        return Results.ValidationProblem(errores);

    var e = new Estudiante
    {
        Nombre = dto.Nombre,
        Correo = dto.Correo,
        Carrera = dto.Carrera,
        Creado = DateTime.UtcNow
    };

    db.Estudiantes.Add(e);
    await db.SaveChangesAsync();

    return Results.Created($"/v1/estudiantes/{e.Id}",
        new EstudianteSalidaDto(e.Id, e.Nombre, e.Correo, e.Carrera, e.Creado));
});

// UPDATE
grupo.MapPut("/{id:int}", async (int id, EstudianteEntradaDto dto, AppDbContext db) =>
{
    var errores = ValidadorEstudiante.Validar(dto);
    if (errores.Count > 0)
        return Results.ValidationProblem(errores);

    var e = await db.Estudiantes.FindAsync(id);
    if (e is null)
        return Results.NotFound();

    e.Nombre = dto.Nombre;
    e.Correo = dto.Correo;
    e.Carrera = dto.Carrera;

    await db.SaveChangesAsync();

    return Results.NoContent();
});

// DELETE
grupo.MapDelete("/{id:int}", async (int id, AppDbContext db) =>
{
    var e = await db.Estudiantes.FindAsync(id);
    if (e is null)
        return Results.NotFound();

    db.Estudiantes.Remove(e);
    await db.SaveChangesAsync();

    return Results.NoContent();
});

app.Run();
