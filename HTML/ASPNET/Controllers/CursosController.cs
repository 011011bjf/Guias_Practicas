using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

[ApiController]
[Route("v1/[controller]")]
public class CursosController : ControllerBase
{
    private readonly AppDbContext _db;

    public CursosController(AppDbContext db)
    {
        _db = db;
    }

    [HttpGet]
    public async Task<IActionResult> Listar()
    {
        var cursos = await _db.Cursos.ToListAsync();
        return Ok(cursos);
    }

    [HttpGet("{id:int}")]
    public async Task<IActionResult> Detalle(int id)
    {
        var curso = await _db.Cursos.FindAsync(id);
        return curso == null ? NotFound() : Ok(curso);
    }

    [HttpPost]
    public async Task<IActionResult> Crear([FromBody] Curso dto)
    {
        _db.Cursos.Add(dto);
        await _db.SaveChangesAsync();
        return CreatedAtAction(nameof(Detalle), new { id = dto.Id }, dto);
    }

    [HttpDelete("{id:int}")]
    public async Task<IActionResult> Eliminar(int id)
    {
        var curso = await _db.Cursos.FindAsync(id);
        if (curso == null) return NotFound();
        _db.Cursos.Remove(curso);
        await _db.SaveChangesAsync();
        return NoContent();
    }
}
