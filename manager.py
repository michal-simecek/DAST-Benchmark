import docker
import subprocess
import glob
import os
import netifaces as ni
import argparse
import yaml

try:
    from netifaces import AF_INET, ifaddresses
except ModuleNotFoundError as e:
    raise SystemExit(f"Requires {e.name} module. Run 'pip install {e.name}' "
                     f"and try again.")

# DOCKER CLIENT INIT ---------------------------------------------------------------------------------------------
client = docker.from_env() # Create a Docker client

# DOCKER PART ----------------------------------------------------------------------------------------------------
def update_docker_compose(docker_compose_path, path):
    endpoints = []
    new_volumes = [
        "./index.html:/var/www/html/index.html"
    ]
    for extension in ['php', 'html']:
        for file_path in glob.glob(f"{path}/**/*.{extension}", recursive=True):
            new_volumes += ["." + file_path + ":/var/www/html/" + '-'.join(file_path.split('/')[4:])]
            if not '-'.join(file_path.split('/')[4:])[0] == '_':
                endpoints += ['-'.join(file_path.split('/')[4:])]

    with open(docker_compose_path, 'r') as file:
        docker_compose = yaml.safe_load(file)

    docker_compose['services']['php']['volumes'] = new_volumes
    new_volumes = new_volumes[:] # creating new copy so previous list is not affected
    new_volumes += ["./default.conf:/etc/nginx/conf.d/default.conf"]
    docker_compose['services']['nginx-index']['volumes'] = new_volumes

    with open(docker_compose_path, 'w') as file:
        yaml.safe_dump(docker_compose, file)

    return endpoints

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

def start_containers():
    find_and_run_compose_files("./modules")
    index_endpoints = update_docker_compose("./nginx/docker-compose.yml","./modules/single-nginx")
    run_compose_file("./nginx/docker-compose.yml") # run webserver with router page
    generate_router(index_endpoints)

def stop_containers():
    containers = client.containers.list()
    for container in containers:
        container.stop()

def remove_containers():
    containers = client.containers.list(all=True)
    for container in containers:
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

def generate_router(index_endpoint_list):
    containers = client.containers.list() # List running containers
    html = "<html><body>"
    ip = select_interface_return_IP(get_interfaces())
    for container in containers: 
        port_data = container.attrs['NetworkSettings']['Ports'] # Get the port mappings
        for exposed_port, port_info in port_data.items():
            if port_info:
                host_port = port_info[0]['HostPort']
                if host_port == "80":
                    for index_endpoint in index_endpoint_list:
                        html += f"<p><a href='http://{ip}/{index_endpoint}'>nginx-index/{index_endpoint}</a></p>"
                    continue
                if exposed_port == "80/tcp":
                    html += f"<p><a href='http://{ip}:{host_port}'>{container.name}:{host_port}</a></p>" # Add a link to the HTML file for each exposed http port
                if exposed_port == "443/tcp":
                    html += f"<p><a href='https://{ip}:{host_port}'>{container.name}:{host_port}</a></p>" # Add a link to the HTML file for each exposed https port
    html += "</body></html>\n"

    with open("nginx/index.html", "w") as file: # Write the HTML file
        file.write(html)

def remove_router(filepath):
    if os.path.isfile(filepath):
        os.remove(filepath)

# MAIN ---------------------------------------------------------------------------------------------------------

def main():
    parser = argparse.ArgumentParser(description="Manage DAST Benchmark.")
    
    group = parser.add_mutually_exclusive_group(required=True)
    group.add_argument('--start', action='store_true', help='Start all Docker containers')
    group.add_argument('--stop', action='store_true', help='Stop all Docker containers')
    group.add_argument('--remove', action='store_true', help='Remove all Docker containers')
    group.add_argument('--restart', action='store_true', help='Remove all Docker containers and recreate them')

    args = parser.parse_args()

    if args.start:
        start_containers()
    elif args.stop:
        stop_containers()
    elif args.remove:
        remove_router("web/index.html")
        remove_containers()
    elif args.restart:
        stop_containers()
        remove_router("web/index.html")
        remove_containers()
        start_containers()
    else:
        print("No valid action provided.")
        sys.exit(1)

if __name__ == "__main__":
    main()