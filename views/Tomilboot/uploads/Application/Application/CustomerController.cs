using Castle.Core.Resource;
using FermaOrders.API.Controllers.Response;
using FermaOrders.Application.Dto.Components.Customer;
using FermaOrders.Application.Interface.Components;
using FermaOrders.Application.Service.Components;
using FermaOrders.Domain.Entities;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using System.Data;
using System.Net;

namespace FermaOrders.API.Controllers.Application
{
    [Route("api/[controller]")]
    [ApiController]
    public class CustomerController : ControllerBase
    {
        private readonly ICustomerService _customerService;

        public CustomerController(ICustomerService customerService)
        {
            _customerService = customerService;
        }

        // Crear un asiento
        [Authorize(Roles = "Admin")]
        [HttpPost]
        [ProducesResponseType(StatusCodes.Status200OK)]
        [ProducesResponseType(StatusCodes.Status403Forbidden)]
        [ProducesResponseType(StatusCodes.Status500InternalServerError)]
        public async Task<IActionResult> CreateCustomerAsync([FromBody] CustomerCreateDto customerDto)
        {
            var response = new RespuestaAPI();
            try
            {
                if (customerDto == null)
                {
                    return BadRequest("Invalid customer data.");
                }

                var customer = await _customerService.CreateCustomerAsync(customerDto);
                response.Result = customer;
                response.IsSuccess = true;
                response.StatusCode = HttpStatusCode.OK;
                return Ok(response);
            }
            catch (Exception ex)
            {
                response.StatusCode = HttpStatusCode.InternalServerError;
                response.IsSuccess = false;
                response.ErrorMessages.Add($"Error: {ex.Message}");
                return StatusCode(500, response);
            }
        }

        // Obtener un asiento por ID
        [Authorize(Roles = "Admin")]
        [HttpGet("{id}")]
        [ProducesResponseType(StatusCodes.Status200OK)]
        [ProducesResponseType(StatusCodes.Status403Forbidden)]
        [ProducesResponseType(StatusCodes.Status500InternalServerError)]
        public async Task<IActionResult> GetCustomerByIdAsync(int id)
        {
            var response = new RespuestaAPI();

            try
            {
                var customer = await _customerService.GetCustomerIdAsync(id);
                if (customer == null)
                {
                    return BadRequest("Invalid customer data.");
                }

                response.Result = customer;
                response.IsSuccess = true;
                response.StatusCode = HttpStatusCode.OK;
                return Ok(response);
            }
            catch (Exception ex)
            {
                response.StatusCode = HttpStatusCode.InternalServerError;
                response.IsSuccess = false;
                response.ErrorMessages.Add($"Error: {ex.Message}");
                return StatusCode(500, response);
            }

            
        }

        [Authorize(Roles = "Admin")]
        [HttpPut("{id}")]
        [ProducesResponseType(StatusCodes.Status200OK)]
        [ProducesResponseType(StatusCodes.Status400BadRequest)]
        [ProducesResponseType(StatusCodes.Status500InternalServerError)]
        public async Task<IActionResult> UpdateCustomerAsync(int id, [FromBody] CustomerUpdateDto customerDto)
        {
            var response = new RespuestaAPI();

            try
            {
                if (id != customerDto.Id)
                {
                    return BadRequest("ID mismatch.");
                }
                await _customerService.UpdateCustomerDto(customerDto);

                response.IsSuccess = true;
                response.StatusCode = HttpStatusCode.OK;
                response.Result = new { message = "Customer updated successfully." };
                return Ok(response);
            }
            catch (Exception ex)
            {
                response.StatusCode = HttpStatusCode.InternalServerError;
                response.IsSuccess = false;
                response.ErrorMessages.Add($"Error: {ex.Message}");
                return StatusCode(500, response);
            }
        }

        // Eliminar un asiento
        [Authorize(Roles = "Admin")]
        [HttpDelete("{id}")]
        [ProducesResponseType(StatusCodes.Status204NoContent)]
        [ProducesResponseType(StatusCodes.Status404NotFound)]
        [ProducesResponseType(StatusCodes.Status500InternalServerError)]
        public async Task<IActionResult> DeleteCustomerAsync(int id)
        {
            var response = new RespuestaAPI();
            try
            {
                await _customerService.DeleteCustomerAsync(id);
                return NoContent();
            }
            catch (KeyNotFoundException ex)
            {
                response.StatusCode = HttpStatusCode.NotFound;
                response.IsSuccess = false;
                response.ErrorMessages.Add($"Error: {ex.Message}");
                return NotFound(response);
            }
            catch (Exception ex)
            {
                response.StatusCode = HttpStatusCode.InternalServerError;
                response.IsSuccess = false;
                response.ErrorMessages.Add($"Error: {ex.Message}");
                return StatusCode(500, response);
            }
        }


        [Authorize(Roles = "Admin")]
        [HttpGet("customer")]
        [ProducesResponseType(StatusCodes.Status200OK)]
        [ProducesResponseType(StatusCodes.Status403Forbidden)]
        [ProducesResponseType(StatusCodes.Status500InternalServerError)]
        public async Task<IActionResult> GetCustomerList()
        {
            var response = new RespuestaAPI();

            try
            {
                var customers = await _customerService.ListCustomer();

                response.Result = customers;
                response.IsSuccess = true;
                response.StatusCode = (HttpStatusCode)(int)HttpStatusCode.OK;

                return Ok(response);
            }
            catch (Exception ex)
            {
                response.IsSuccess = false;
                response.StatusCode = (HttpStatusCode)(int)HttpStatusCode.InternalServerError;
                response.ErrorMessages.Add($"Error: {ex.Message}");

                return StatusCode(500, response);
            }

            // Redundante, pero garantiza que todas las rutas retornan algo
            // No debería alcanzarse nunca
            return StatusCode(500, new RespuestaAPI
            {
                IsSuccess = false,
                StatusCode = (HttpStatusCode)(int)HttpStatusCode.InternalServerError,
                ErrorMessages = new List<string> { "Ocurrió un error inesperado." }
            });
        }
    }
}
