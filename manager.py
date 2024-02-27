import docker
import subprocess
import glob
import os
import netifaces as ni

try:
    from netifaces import AF_INET, ifaddresses
except ModuleNotFoundError as e:
    raise SystemExit(f"Requires {e.name} module. Run 'pip install {e.name}' "
                     f"and try again.")

# DOCKER CLIENT INIT ---------------------------------------------------------------------------------------------
client = docker.from_env() # Create a Docker client

# DOCKER PART ----------------------------------------------------------------------------------------------------
def run_compose_file(file_path):
    result = subprocess.run(
        ["docker-compose", "-f", file_path, "up", "-d"],
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE,
    )
    if result.returncode != 0:
        print(f"Failed to start compose project {file_path}: {result.stderr.decode()}")
    else:
        print(f"Started compose project {file_path}")

def find_and_run_compose_files(directory):
    for file_path in glob.glob(f"{directory}/**/docker-compose.yml", recursive=True):
        run_compose_file(file_path)


def stop_and_remove_containers():
    containers = client.containers.list() # List running containers
    for container in containers:
        container.stop()
        container.remove()

def get_ip_address(interface: str) -> str:
    return ifaddresses(interface)[AF_INET][0]['addr']

# SELECT INTERFACE PART -----------------------------------------------------------------------------------------

def get_interfaces():
    interfaces = ni.interfaces()
    interface_dict = {}
    for interface in interfaces:
        try:
            ip = ni.ifaddresses(interface)[ni.AF_INET][0]['addr']
            interface_dict[interface] = ip
        except KeyError:
            pass
    return interface_dict

def select_interface_return_IP(interface_dict):
    print("Available network interfaces and their IP addresses:")
    for i, (interface, ip) in enumerate(interface_dict.items()):
        print(f"{i+1}. {interface} - {ip}")
    selected = int(input("Enter the number of the interface you want to select: ")) - 1
    selected_interface = list(interface_dict.keys())[selected]
    print(f"You have selected: {selected_interface}")
    return ni.ifaddresses(selected_interface)[ni.AF_INET][0]['addr']

# ROUTER -------------------------------------------------------------------------------------------------------

def generate_router():
    containers = client.containers.list() # List running containers
    html = "<html><body>" # Start the HTML file
    ip = select_interface_return_IP(get_interfaces())
    for container in containers: 
        port_data = container.attrs['NetworkSettings']['Ports'] # Get the port mappings
        for exposed_port, port_info in port_data.items():
            if port_info:
                host_port = port_info[0]['HostPort']
                if exposed_port == "80/tcp":
                    html += f"<p><a href='http://{ip}:{host_port}'>{container.name} ({exposed_port} -> {host_port})</a></p>" # Add a link to the HTML file for each exposed http port
                if exposed_port == "443/tcp":
                    html += f"<p><a href='https://{ip}:{host_port}'>{container.name} ({exposed_port} -> {host_port})</a></p>" # Add a link to the HTML file for each exposed https port

    html += "</body></html>" # End the HTML file
    with open("web/index.html", "w") as file: # Write the HTML file
        file.write(html)

def remove_router(filepath):
    if os.path.isfile(filepath):
        os.remove(filepath)
        print(f"File {filepath} has been removed.")
    else:
        print(f"No such file: {filepath}")

# MAIN ---------------------------------------------------------------------------------------------------------

def main():
    try:
        remove_router("web/index.html") # Delete router page
    except Exception:
        pass
    input("Press Enter to confirm stopping and removing all running docker containers...") # Wait for user input
    stop_and_remove_containers() # Stop and remove the containers
    find_and_run_compose_files("./modules") # You can call the function with the path to the directory where your compose files are
    run_compose_file("./docker-compose.yml") # run webserver with router
    generate_router() # Create router html page with links to all modules
    input("Press Enter to stop and remove all containers...") # Wait for user input
    input("Press Enter again to stop and remove all containers...") # Wait for user input
    input("Press Enter for the last time to stop and remove all containers for real this time...") # Wait for user input

    stop_and_remove_containers() # Stop and remove the containers
    remove_router("web/index.html") # Delete router page

if __name__ == "__main__":
    main()