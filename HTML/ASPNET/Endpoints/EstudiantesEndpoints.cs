using Microsoft.AspNetCore.Mvc;

public static class EstudiantesEndpoints
{
    public static void MapEstudiantesEndpoints(this IEndpointRouteBuilder routes)
    {
        var grupo = routes.MapGroup("/v1/estudiantes").RequireAuthorization();

        grupo.MapGet("/", async (IEstudianteService svc, int pagina = 1, int tamano = 10, string? orden = null, string? buscar = null) =>
        {
            var (datos, total) = await svc.ObtenerTodosAsync(pagina, tamano, orden, buscar);
            return Results.Ok(new { pagina, tamano, total, datos });
        });

        grupo.MapGet("/{id:int}", async (int id, IEstudianteService svc) =>
        {
            var e = await svc.ObtenerPorIdAsync(id);
            return e is null ? Results.NotFound() : Results.Ok(e);
        });

        grupo.MapPost("/", async (EstudianteEntradaDto dto, IEstudianteService svc) =>
        {
            var (estudiante, errores) = await svc.CrearAsync(dto);
            if (errores != null) return Results.ValidationProblem(errores);
            return Results.Created($"/v1/estudiantes/{estudiante!.Id}", estudiante);
        });

        grupo.MapPut("/{id:int}", async (int id, EstudianteEntradaDto dto, IEstudianteService svc) =>
        {
            var errores = await svc.ActualizarAsync(id, dto);
            if (errores != null)
            {
                if (errores.ContainsKey("id")) return Results.NotFound();
                return Results.ValidationProblem(errores);
            }
            return Results.NoContent();
        });

        grupo.MapDelete("/{id:int}", async (int id, IEstudianteService svc) =>
        {
            var eliminado = await svc.EliminarAsync(id);
            return eliminado ? Results.NoContent() : Results.NotFound();
        }).RequireAuthorization(p => p.RequireRole("admin")); // Ejercicio 13.3.2
    }
}
